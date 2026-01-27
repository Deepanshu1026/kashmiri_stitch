<?php
function sendEmail($toEmail, $toName, $subject, $htmlContent) {
    $apiKey = 'xsmtpsib-d5121ba8bb3216bd8a45ef16c60561b33490475749fbf589872bd12fe1ca10a1-laEOa44yo5QzLlVY'; // In production, use environment variables
    $url = 'https://api.brevo.com/v3/smtp/email';

    $data = [
        "sender" => [
            "name" => "Kashmiri Stitch",
            "email" => "avisaexpertstm@gmail.com"
        ],
        "to" => [
            [
                "email" => $toEmail,
                "name" => $toName
            ]
        ],
        "subject" => $subject,
        "htmlContent" => $htmlContent
    ];

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'accept: application/json',
        'api-key: ' . $apiKey,
        'content-type: application/json'
    ]);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    
    $response = curl_exec($ch);
    $err = curl_error($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    // Logging
    $logMessage = "[" . date('Y-m-d H:i:s') . "] To: $toEmail | Subject: $subject | HTTP: $http_code | Result: " . ($err ? "Error: $err" : $response) . "\n";
    file_put_contents('../email_log.txt', $logMessage, FILE_APPEND);

    if ($err || $http_code >= 400) {
        return ['status' => false, 'message' => "Error (HTTP $http_code): " . ($err ? $err : $response)];
    } else {
        return ['status' => true, 'response' => json_decode($response, true)];
    }
}
?>
