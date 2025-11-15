<?php
require_once __DIR__ . "/callOpenAI.php";

class TextReview
{
    // -------------------------------
    // Validate the AI returned array
    // -------------------------------
    public static function validateStructure($response)
    {
        if ($response === null) {
            return "Response not parsable to JSON";
        }

        if (!is_array($response)) {
            return "Response must be a JSON object or array";
        }

        // Empty response is allowed
        if (empty($response)) {
            return null;
        }

        // If the AI returned multiple objects, validate each one
        // foreach ($response as $item) {

        //     if (!is_array($item)) {
        //         return "Each returned element must be an associative array";
        //     }

        //     // No strict required fields → skip
        // }

        return null; // all good
    }



    // ----------------------------------
    // Extract GENERAL DATA from text
    // ----------------------------------
    public static function reviewTextGenenal($text, $retry = 0, $error = null, $previousResponse = null)
    {
        if ($retry > 4) {
            return ["error" => "Failed to get a valid structure from AI"];
        }

        if ($retry === 0) {
            $instruction = <<<EOD
Extract health-related information from the following text.

Return ONLY a JSON OBJECT or ARRAY containing the following fields 
(omit fields that do not exist in the text):

- exercise_minutes
- walk_minutes
- steps
- sleep_hour
- wakeup_hour
- caffeine
- calories_intake  (estimate if possible)
- calories_burn    (estimate if possible)

Text:
$text

Return ONLY JSON. No explanations. No text.
EOD;
        } else {
            $instruction = <<<EOD
Your previous response was invalid JSON.

Error: $error

Previous response:
$previousResponse

Try again.

Extract and return ONLY these fields (omit missing ones):

- exercise_minutes
- walk_minutes
- steps
- sleep_hour
- caffeine
- calories_intake
- calories_burn

Text:
$text

Return ONLY JSON.
EOD;
        }

        // Call AI
        $response = requestOpenAi($instruction);
        $responseDecoded = json_decode($response, true);

        if (!isset($responseDecoded['choices'][0]['message']['content'])) {
            return ["error" => "Invalid OpenAI response"];
        }

        $content = $responseDecoded['choices'][0]['message']['content'];
        $parsed = json_decode($content, true);

        // Validate result
        $validationError = self::validateStructure($parsed);

        if ($validationError !== null) {
            return self::reviewTextGenenal(
                $text,
                $retry + 1,
                $validationError,
                $content
            );
        }

        return $parsed;
    }




    // ----------------------------------
    // Extract MEAL DATA from text
    // ----------------------------------
    public static function reviewTextforMeals($text, $retry = 0, $error = null, $previousResponse = null)
    {
        if ($retry > 4) {
            return ["error" => "Failed to get a valid meals structure from AI"];
        }

        if ($retry === 0) {
            $instruction = <<<EOD
Extract MEAL information from the text.

Return ONLY a JSON OBJECT or ARRAY with these fields:

- meals
- datetime
- meal_categories
- calories_intake  (estimate)

Text:
$text

Return ONLY JSON. No explanations.
EOD;
        } else {
            $instruction = <<<EOD
Your previous meal extraction was invalid.

Error: $error

Previous response:
$previousResponse

Try again.

Extract ONLY the following fields:

- meals
- datetime
- meal_categories
- calories_intake

Text:
$text

Return ONLY JSON. No explanations.
EOD;
        }

        // Call AI
        $response = requestOpenAi($instruction);
        $responseDecoded = json_decode($response, true);

        if (!isset($responseDecoded['choices'][0]['message']['content'])) {
            return ["error" => "Invalid OpenAI response"];
        }

        $content = $responseDecoded['choices'][0]['message']['content'];
        $parsed = json_decode($content, true);

        // Validate
        $validationError = self::validateStructure($parsed);

        if ($validationError !== null) {
            return self::reviewTextforMeals(
                $text,
                $retry + 1,
                $validationError,
                $content
            );
        }

        return $parsed;
    }

    
}
?>
