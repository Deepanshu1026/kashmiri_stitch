<?php
require_once __DIR__ . '/../vendor/autoload.php'; // Adjust path if needed

function sendEmail($toEmail, $toName, $subject, $htmlContent) {
    // API key config
    $config = SendinBlue\Client\Configuration::getDefaultConfiguration()
        ->setApiKey('api-key', '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');

    $apiInstance = new SendinBlue\Client\Api\TransactionalEmailsApi(
        new GuzzleHttp\Client(),
        $config
    );

    // Email data
    $sendSmtpEmail = new \SendinBlue\Client\Model\SendSmtpEmail([
        'subject' => $subject,
        'sender' => [
            'name'  => 'Kashmiri Stitch',
            'email' => 'bishtdepanshu321@gmail.com' // verified sender
        ],
        'to' => [
            [
                'email' => $toEmail,
                'name'  => $toName
            ]
        ],
        'htmlContent' => $htmlContent
    ]);

    try {
        $result = $apiInstance->sendTransacEmail($sendSmtpEmail);
        
        // Logging
        $logMessage = "[" . date('Y-m-d H:i:s') . "] To: $toEmail | Subject: $subject | Result: Success (ID: " . $result->getMessageId() . ")\n";
        file_put_contents(__DIR__ . '/../email_log.txt', $logMessage, FILE_APPEND);

        return ['status' => true, 'response' => $result];

    } catch (Exception $e) {
        // Logging Error
        $logMessage = "[" . date('Y-m-d H:i:s') . "] To: $toEmail | Subject: $subject | Error: " . $e->getMessage() . "\n";
        file_put_contents(__DIR__ . '/../email_log.txt', $logMessage, FILE_APPEND);

        return ['status' => false, 'message' => 'Error: ' . $e->getMessage()];
    }
}
?>
