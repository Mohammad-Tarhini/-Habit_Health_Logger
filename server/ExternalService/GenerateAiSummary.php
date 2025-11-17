<?php
class GenerateAiSummary{

    private static function isJsonLike($text)
     {
     $trim = trim($text);
    
     return str_starts_with($trim, '{') ||
            str_starts_with($trim, '[') ||
            json_decode($trim, true) !== null;
     }


   public static function summarizeJsonText($jsonData, $retry = 0, $error = null, $previous = null)
{
    if ($retry > 4) {
        return [
            "success" => false,
            "error"   => "Failed to generate summary from AI"
        ];
    }

    $jsonString = json_encode($jsonData);

    if ($retry === 0) {
        $instruction = <<<EOD
Summarize the following weekly data  as you are dietition and also it contain your advices and let the paragraph be very small.

Return ONLY plain text (no json).

Data:
$jsonString
EOD;
    } else {
        $instruction = <<<EOD
Your previous summary response was invalid.

Error: $error
Previous AI text:
$previous

Try again and return ONLY text.
Data:
$jsonString
EOD;
    }

    // Call AI
    $response = requestOpenAi($instruction);

    // if (!isset($response['choices'][0]['message']['content'])) {
    //     return [
    //         "success" => false,
    //         "error"   => "Invalid AI response structure"
    //     ];
    // }
    if (is_string($response)) {
    $response = json_decode($response, true);
}

    $text = $response['choices'][0]['message']['content'];

    // Validate: text should NOT be empty
    if (strlen(trim($text)) < 5) {
        return self::summarizeJsonText(
            $jsonData,
            $retry + 1,
            "AI returned empty summary",
            $text
        );
    }

    return [
        "success" => true,
        "summary" => $text
    ];
}

public static function nutritionCoachCard($jsonData,$jsonMeals, $retry = 0, $error = null, $previous = null)
{
    if ($retry > 4) {
        return [
            "success" => false,
            "error"   => "Failed to generate nutrition coach card"
        ];
    }

    $jsonStringdata = json_encode($jsonData);
    $jsonStringmeals=Json_encode($jsonMeals);

    if ($retry == 0) {
        $instruction = <<<EOD
You are a professional AI Nutrition Coach.

Analyze the user's meals for TODAY (JSON data below) and return ONLY a short TEXT summary containing:

1. Estimated calories today
3. Suggest the NEXT healthy meal the user should eat according to his data 
4. Include 1 short tip for better nutrition

Return ONLY clean text. No JSON.

Data:
$jsonStringdata
and this is meals today i eated :
$jsonStringmeals
EOD;
    } else {
        $instruction = <<<EOD
Your previous nutrition card response was invalid.

Error: $error
Previous response:
$previous

Try again. Return ONLY short text (no JSON).

Data:
$jsonStringdata
and this is meals today i eated :
$jsonStringmeals
EOD;
    }

    $response = requestOpenAi($instruction);

    // if (!isset($response['choices'][0]['message']['content'])) {
    //     return [
    //         "success" => false,
    //         "error"   => "Invalid AI structure"
    //     ];
    // }

     if (is_string($response)) {
    $response = json_decode($response, true);
     }
    $text = trim($response['choices'][0]['message']['content']);

    if (strlen($text) < 10) {
        return self::nutritionCoachCard($jsonMeals, $retry + 1, "AI returned empty or too short", $text);
    }

    return [
        "success" => true,
        "summary" => $text
    ];
}


 public static function RecieveTextAndsendTextincludesugestion($text, $retry = 0, $error = null, $previous = null)
{
    if ($retry > 4) {
        return [
            "success" => false,
            "error"   => "Failed to generate summary from AI"
        ];
    }

   

    if ($retry === 0) {
        $instruction = <<<EOD
give me small paragraph suggest to preservation for her healthy   accordinf to this text 


text:
$text
EOD;
    } else {
        $instruction = <<<EOD
Your previous paragraph response was invalid.

Error: $error
Previous AI text:
$previous

Try again and return ONLY text.
Data:
$text
EOD;
    }

    // Call AI
    $response = requestOpenAi($instruction);

    if (!isset($response['choices'][0]['message']['content'])) {
        return [
            "success" => false,
            "error"   => "Invalid AI response structure"
        ];
    }

    $text = $response['choices'][0]['message']['content'];

    // Validate: text should NOT be empty
    if (strlen(trim($text)) < 5) {
        return self::RecieveTextAndsendTextincludesugestion(
            $jsonData,
            $retry + 1,
            "AI returned empty summary",
            $text
        );
    }

    return [
        "success" => true,
        "summary" => $text
    ];
}



}

?>