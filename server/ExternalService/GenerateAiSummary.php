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
Summarize the following weekly data as a dietitian. 
Include short advice and keep it very small.

Return ONLY plain text.

Data:
$jsonString
EOD;
    } else {
        $instruction = <<<EOD
Your previous response was invalid.

Error: $error
Previous AI text:
$previous

Try again. Return ONLY plain text.

Data:
$jsonString
EOD;
    }

    // --- CALL OPENAI ---
    $result = requestOpenAi($instruction);

    // If response is a string → decode JSON
    if (is_string($result)) {
        $response = json_decode($result, true);
    } else {
        $response = $result;
    }

    // If failed to decode JSON
    if ($response === null) {
        return [
            "success" => false,
            "error" => "Failed to decode AI response",
            "raw" => $result
        ];
    }

    // If API error returned
    if (isset($response["error"])) {
        return [
            "success" => false,
            "error" => $response["error"]["message"] ?? "Unknown AI error"
        ];
    }

    // If AI structure is invalid
    if (!isset($response["choices"][0]["message"]["content"])) {
        return [
            "success" => false,
            "error" => "AI returned invalid structure",
            "raw" => $response
        ];
    }

    $text = $response["choices"][0]["message"]["content"];

    // Validate summary
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
Give me a small, simple health advice paragraph for this person based on the following text. 

Return ONLY plain text.

Text:
$text
EOD;
    } else {
        $instruction = <<<EOD
Your previous response was invalid.

Error: $error
Previous AI text:
$previous

Try again and return ONLY plain text.

Text:
$text
EOD;
    }

    // Call AI
    $result = requestOpenAi($instruction);

    // Decode JSON
    if (is_string($result)) {
        $response = json_decode($result, true);
    } else {
        $response = $result;
    }

    // If decoding failed
    if ($response === null) {
        return [
            "success" => false,
            "error" => "Failed to decode AI response",
            "raw"   => $result
        ];
    }

    // If the response contains an error
    if (isset($response["error"])) {
        return [
            "success" => false,
            "error"   => $response["error"]["message"] ?? "Unknown AI error",
            "raw" => $response
        ];
    }

    // Validate structure
    if (!isset($response["choices"][0]["message"]["content"])) {
        return [
            "success" => false,
            "error"   => "AI returned invalid structure",
            "raw"     => $response
        ];
    }

    $aiText = $response["choices"][0]["message"]["content"];

    // Ensure text is not empty
    if (strlen(trim($aiText)) < 5) {
        return self::RecieveTextAndsendTextincludesugestion(
            $text,
            $retry + 1,
            "AI returned empty summary",
            $aiText
        );
    }

    return [
        "success" => true,
        "summary" => $aiText
    ];
}


}

?>