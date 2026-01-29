<?php
require_once __DIR__ . '/vendor/autoload.php';

// Configure API key
$config = SendinBlue\Client\Configuration::getDefaultConfiguration()->setApiKey('api-key', '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');

$apiInstance = new SendinBlue\Client\Api\EmailCampaignsApi(
    new GuzzleHttp\Client(),
    $config
);

$emailCampaigns = new \SendinBlue\Client\Model\CreateEmailCampaign();

// Settings
$emailCampaigns['name'] = "Test Campaign API";
$emailCampaigns['subject'] = "Test Subject";
// IMPORTANT: This email MUST be verified in your Brevo account (Senders & IP)
$emailCampaigns['sender'] = ["name" => "Kashmiri Stitch", "email" => "avisaexpertstm@gmail.com"];
$emailCampaigns['type'] = "classic";
$emailCampaigns['htmlContent'] = "This is a test campaign created via API.";

// Recipient List ID (Check your Brevo Lists)
$emailCampaigns['recipients'] = ["listIds" => [2]]; 

// Schedule (Must be in the future)
$emailCampaigns['scheduledAt'] = date('Y-m-d H:i:s', strtotime('+5 hours'));

try {
    echo "Creating Campaign...\n";
    $result = $apiInstance->createEmailCampaign($emailCampaigns);
    print_r($result);
} catch (Exception $e) {
    echo 'Error: ' . $e->getMessage() . PHP_EOL;
}
?>
