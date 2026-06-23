<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/Exception.php';
require 'PHPMailer/PHPMailer.php';
require 'PHPMailer/SMTP.php';

header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    http_response_code(403);
    echo json_encode(['status' => 'error', 'message' => 'There was a problem with your submission, please try again.']);
    exit;
}

$fullName = isset($_POST['fullName']) ? strip_tags(trim($_POST['fullName'])) : '';
$email    = isset($_POST['email'])    ? filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL) : '';
$phone    = isset($_POST['phone'])    ? strip_tags(trim($_POST['phone'])) : '';
$orgType  = isset($_POST['orgType'])  ? strip_tags(trim($_POST['orgType'])) : '';
$interest = isset($_POST['interest']) ? strip_tags(trim($_POST['interest'])) : '';
$resume   = isset($_POST['resume'])   ? filter_var(trim($_POST['resume']), FILTER_SANITIZE_URL) : '';
$message  = isset($_POST['message'])  ? strip_tags(trim($_POST['message'])) : '';

if (empty($fullName) || empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'Please fill all required fields with valid details.']);
    exit;
}

$mail = new PHPMailer(true);

try {
    // Native PHP server mail — From must match the website domain to avoid spam filters.
    $mail->isMail();
    $mail->setFrom('info@healtho.pro', 'HealthO Pro Website');
    $mail->addReplyTo($email, $fullName);
    $mail->Sender = 'info@healtho.pro'; // Return-Path for shared hosts

    // Notification recipients
    $mail->addAddress('sales@healtho.pro');
    $mail->addAddress('info@healtho.pro');

    // Subject reflects the type of submission
    $subjectType = ($orgType === 'Job Application') ? 'New Career Application' : 'New Website Enquiry';
    $mail->isHTML(false);
    $mail->Subject = "$subjectType - HealthO Pro";

    $body  = "$subjectType — HealthO Pro\n";
    $body .= str_repeat('-', 40) . "\n";
    $body .= "Name: $fullName\n";
    $body .= "Email: $email\n";
    $body .= "Phone: $phone\n";
    $body .= "Organization / Type: $orgType\n";
    $body .= "Interested In / Position: $interest\n";
    if (!empty($resume)) {
        $body .= "Resume / CV: $resume\n";
    }
    $body .= str_repeat('-', 40) . "\n";
    $body .= "Message:\n$message\n";

    $mail->Body = $body;

    $mail->send();
    http_response_code(200);
    echo json_encode(['status' => 'success', 'message' => 'Your message has been sent successfully.']);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => "Could not send your message. Please email sales@healtho.pro directly."]);
}
?>
