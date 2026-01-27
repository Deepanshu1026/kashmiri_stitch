<?php
// Check if the Brevo Library exists
$autoloadPath = __DIR__ . "/vendor/autoload.php";

if (file_exists($autoloadPath)) {
    require_once($autoloadPath);

    // Instantiate the client
    // Configure API key authorization: api-key
    $config = SendinBlue\Client\Configuration::getDefaultConfiguration()->setApiKey('api-key', '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');

    $apiInstance = new SendinBlue\Client\Api\EmailCampaignsApi(
        new GuzzleHttp\Client(),
        $config
    );

    $emailCampaigns = new \SendinBlue\Client\Model\CreateEmailCampaign();

    // Define the campaign settings
    $emailCampaigns['name'] = "Campaign sent via the API";
    $emailCampaigns['subject'] = "My subject";
    $emailCampaigns['sender'] = ["name" => "Kashmiri Stitch", "email" => "avisaexpertstm@gmail.com"];
    $emailCampaigns['type'] = "classic";
    
    // Content that will be sent
    $emailCampaigns['htmlContent'] = "Congratulations! You successfully sent this example campaign via the Brevo API.";
    
    // Select the recipients
    // NOTE: listIds must refer to existing lists in your Brevo account
    $emailCampaigns['recipients'] = ["listIds" => [2, 7]]; 
    
    // Schedule the sending
    // NOTE: scheduledAt must be in the future
    $emailCampaigns['scheduledAt'] = date('Y-m-d H:i:s', strtotime('+1 hour'));

    // Make the call to the client
    try {
        echo "Creating Campaign...\n";
        $result = $apiInstance->createEmailCampaign($emailCampaigns);
        print_r($result);
    } catch (Exception $e) {
        echo 'Exception when calling EmailCampaignsApi->createEmailCampaign: ', $e->getMessage(), PHP_EOL;
    }

} else {
    echo "<h1>Brevo Library Not Found</h1>";
    echo "<p>The file <code>$autoloadPath</code> was not found.</p>";
    echo "<p>Please properly install the Brevo PHP Library to use the SDK code.</p>";
    echo "<hr>";
    
    // Fallback: Use the standard CURL method (Transactional Email)
    echo "<h3>Fallback: Sending Transactional Email via CURL</h3>";
    
    function sendTransactionalEmailCurl($toEmail, $toName, $subject, $htmlContent) {
        $apiKey = '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';
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

        if ($err || $http_code >= 400) {
            echo "Error (HTTP $http_code): " . ($err ? $err : $response);
        } else {
            echo "Email Sent Successfully!<br>";
            echo "Response: " . $response;
        }
    }

    sendTransactionalEmailCurl("avisaexpertstm@gmail.com", "Test Receiver", "Fallback Test Email", "<p>This is sent via CURL because the SDK is missing.</p>");
}
?>
