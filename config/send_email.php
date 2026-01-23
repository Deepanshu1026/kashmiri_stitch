<?php
function sendEmail($toEmail, $toName, $subject, $htmlContent) {
    $apiKey = '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'; // In production, use environment variables
    $url = 'https://api.brevo.com/v3/smtp/email';

    $data = [
        "sender" => [
            "name" => "Kashmiri Stitch",
            "email" => "no-reply@kashmiristitch.com"
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
    curl_close($ch);

    if ($err) {
        return ['status' => false, 'message' => "cURL Error: " . $err];
    } else {
        return ['status' => true, 'response' => json_decode($response, true)];
    }
}
?>
