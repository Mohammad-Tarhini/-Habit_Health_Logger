<?php
include_once __DIR__."/callOpenAI.php";

function validateStructure($response) {
    /*
        we expect this output from the AI: 
        
        {"exercise_minutes":"20","walk_minutes":"20","steps":"23","sleep_hour":"22:33:2","wakeup_hour":"43324","caffeine":4,"calories_burn":5,"calories_intake":43,"calories_burn":39,"start_date":"32","end_date":"3/4/2000"}
           
        
        In this function it should be decoded into an associtive array
    */
    
    if($response == null){
        return "Response not parsable to JSON";
    }

    // must be an array/object 
    if (is_object($response)) {
        return "Response is not an object ";
    }
    // allow no error cases 
    if (empty($response)) {
        return null; // valid case
    }

    

       /* // check required fields
        if (!isset($item['severity'], $item['issue'], $item['suggestion'])) {
            return "Missing required fields in item at index $index , remember each item must contain severity , issue and suggestion fields";
        }*/


        // type checks
        if (!is_string($item['issue']) || !is_string($item['suggestion'])) {
            return "Issue or suggestion is not a string in item at index $index";
        }
    

    return null;// no errors
}
function reviewText($text , $retry = 0 , $error = null , $previousResponse = null){
    // to avoid infinite recursion
    if($retry > 4) return ["error" => "Failed to receive correct structure from AI"];
    // generate instruction
    if($retry == 0){// if on first try, give initial prompt
        $instruction = <<<EOD
        You are strictly a . I'm going to give you a Text snippet. 
        Read it carefully, data , and return an array of JSON object(s) with these exact fields:
        exercise_minutes	walk_minutes	steps	sleep_hour	wakeup_hour	caffeine	calories_intake	calories_burn	start_date	end_date
        
        Constraints:
       

        Text:
        $code
        Return only the array of JSON object(s), with no explanation or formatting.
        EOD;
    }else{// modify the instruction guiding the AI to the right output
        $instruction = <<<EOD
        I previously asked you to review my Text and find object in it, then return 
        an JSON object with these exact fields:
        exercise_minutes	walk_minutes	steps	sleep_hour	wakeup_hour	caffeine	calories_intake	calories_burn	start_date	end_date

        

        Code :
        $code
        Only return the array of JSON object(s), with no explanation or formatting.

        But you faild to deliver the right struture.
        Your response was :
        $previousResponse
        The mistake you did was : 
        $error
        Please take your time.
        EOD;
    }

    // call api
    $response = requestOpenAi($instruction);
    $responseData = json_decode($response , true);
    
    $content = $responseData['choices'][0]['message']['content'];
    $parsedContent = json_decode($content , true);

    // validate response
    $error = validateStructure($parsedContent);
    if($error != null){
        $previousEncodedResponse = json_encode($parsedContent , JSON_UNESCAPED_UNICODE);// return to json so AI can see response clearly
        return reviewCode($code , $fileExtension, $retry + 1 , $error , $previousEncodedResponse);
    }else{// success
        return $parsedContent;         
    }
}
?>