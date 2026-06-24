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

// Auto-detected geo fields (populated client-side from the visitor's IP)
$city     = isset($_POST['city'])     ? strip_tags(trim($_POST['city']))    : '';
$state    = isset($_POST['state'])    ? strip_tags(trim($_POST['state']))   : '';
$country  = isset($_POST['country'])  ? strip_tags(trim($_POST['country'])) : '';

// Mobile number is now mandatory alongside name & email
if (empty($fullName) || empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL) || empty($phone)) {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'Please fill all required fields (including a valid mobile number).']);
    exit;
}

$locationParts = array_filter([$city, $state, $country], function ($v) { return $v !== ''; });
$location = !empty($locationParts) ? implode(', ', $locationParts) : 'Not detected';

$mail = new PHPMailer(true);

try {
    // Native PHP server mail — From must match the website domain to avoid spam filters.
    $mail->isMail();
    $mail->setFrom('info@healtho.pro', 'HealthO Pro Website');
    $mail->addReplyTo($email, $fullName);
    $mail->Sender = 'info@healtho.pro'; // Return-Path for shared hosts

    // Notification recipients
    $mail->addAddress('digicarelynx@gmail.com');

    // Subject reflects the type of submission
    $subjectType = ($orgType === 'Job Application') ? 'New Career Application' : 'New Website Enquiry';
    $mail->isHTML(true);
    $mail->CharSet = 'UTF-8';
    $mail->Subject = "$subjectType — HealthO Pro";

    $h = function ($s) { return htmlspecialchars($s, ENT_QUOTES, 'UTF-8'); };

    // Build detail rows (only include rows that have a value)
    $rows = [];
    $rows[] = ['Name', $h($fullName)];
    $rows[] = ['Email', '<a href="mailto:' . $h($email) . '" style="color:#0096B7;text-decoration:none;font-weight:600;">' . $h($email) . '</a>'];
    $rows[] = ['Mobile Number', '<a href="tel:' . $h(preg_replace('/[^0-9+]/', '', $phone)) . '" style="color:#0096B7;text-decoration:none;font-weight:600;">' . $h($phone) . '</a>'];
    if ($orgType !== '')  $rows[] = ['Organization / Type', $h($orgType)];
    if ($interest !== '') $rows[] = ['Interested In / Position', $h($interest)];
    if ($resume !== '')   $rows[] = ['Resume / CV', '<a href="' . $h($resume) . '" style="color:#0096B7;text-decoration:none;font-weight:600;">View / Download</a>'];
    $rows[] = ['Location', $h($location) . ' <span style="color:#94a3b8;font-size:11px;">(auto-detected)</span>'];

    $rowsHtml = '';
    $i = 0;
    foreach ($rows as $r) {
        $bg = ($i % 2 === 0) ? '#f6f9fc' : '#ffffff';
        $rowsHtml .= '<tr>'
            . '<td style="padding:13px 18px;background:' . $bg . ';border-bottom:1px solid #e4ecf4;font-size:13px;color:#64748b;font-weight:600;width:40%;vertical-align:top;">' . $r[0] . '</td>'
            . '<td style="padding:13px 18px;background:' . $bg . ';border-bottom:1px solid #e4ecf4;font-size:14px;color:#0f1b33;font-weight:500;vertical-align:top;">' . $r[1] . '</td>'
            . '</tr>';
        $i++;
    }

    $messageHtml = $message !== '' ? nl2br($h($message)) : '<span style="color:#94a3b8;">— No message provided —</span>';
    $emailEsc    = $h($email);
    $replyName   = $h($fullName);
    $dateStr     = date('d M Y, g:i A');
    $year        = date('Y');

    $html = <<<HTML
<!DOCTYPE html>
<html lang="en">
<head><meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1"></head>
<body style="margin:0;padding:0;background:#eef4fa;-webkit-font-smoothing:antialiased;font-family:'Segoe UI',Roboto,Helvetica,Arial,sans-serif;">
  <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="background:#eef4fa;padding:30px 12px;">
    <tr><td align="center">
      <table role="presentation" width="600" cellpadding="0" cellspacing="0" border="0" style="max-width:600px;width:100%;background:#ffffff;border-radius:16px;overflow:hidden;box-shadow:0 8px 28px rgba(15,27,51,.10);">
        <!-- Logo -->
        <tr><td align="center" style="padding:30px 30px 22px;background:#ffffff;">
          <img src="https://healtho.pro/assets/images/logo.png" alt="HealthO Pro — Empowering Healthcare Providers" width="200" style="display:block;border:0;outline:none;width:200px;max-width:62%;height:auto;">
        </td></tr>
        <!-- Accent strip -->
        <tr><td style="height:4px;background:#00B4D8;background:linear-gradient(90deg,#122B5C 0%,#00B4D8 100%);font-size:0;line-height:0;">&nbsp;</td></tr>
        <!-- Title band -->
        <tr><td style="padding:26px 30px;background:#122B5C;">
          <div style="font-size:12px;letter-spacing:.09em;text-transform:uppercase;color:#48CAE4;font-weight:700;">$subjectType</div>
          <div style="font-size:21px;color:#ffffff;font-weight:700;margin-top:5px;">You've received a new submission</div>
          <div style="font-size:13px;color:rgba(255,255,255,.72);margin-top:7px;">Submitted on $dateStr</div>
        </td></tr>
        <!-- Details -->
        <tr><td style="padding:26px 30px 10px;">
          <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="border:1px solid #e4ecf4;border-radius:10px;overflow:hidden;">
            $rowsHtml
          </table>
        </td></tr>
        <!-- Message -->
        <tr><td style="padding:10px 30px 26px;">
          <div style="font-size:13px;color:#64748b;font-weight:600;margin-bottom:9px;">Message</div>
          <div style="background:#f6f9fc;border:1px solid #e4ecf4;border-left:4px solid #00B4D8;border-radius:8px;padding:16px 18px;font-size:14px;color:#334155;line-height:1.65;">$messageHtml</div>
        </td></tr>
        <!-- Reply CTA -->
        <tr><td align="center" style="padding:0 30px 32px;">
          <a href="mailto:$emailEsc" style="display:inline-block;background:#0096B7;background:linear-gradient(90deg,#122B5C 0%,#00B4D8 100%);color:#ffffff;text-decoration:none;font-weight:700;font-size:14px;padding:13px 30px;border-radius:999px;">Reply to $replyName</a>
        </td></tr>
        <!-- Footer -->
        <tr><td style="padding:24px 30px;background:#0C1F45;text-align:center;">
          <div style="font-size:15px;color:#ffffff;font-weight:700;">HealthO Pro</div>
          <div style="font-size:12px;color:rgba(255,255,255,.66);margin-top:4px;">Empowering Healthcare Providers</div>
          <div style="font-size:12px;color:rgba(255,255,255,.55);margin-top:12px;line-height:1.6;">This message was sent automatically from the HealthO Pro website.<br>&copy; $year Healthocare Private Limited. All rights reserved.</div>
        </td></tr>
      </table>
    </td></tr>
  </table>
</body>
</html>
HTML;

    $plain  = "$subjectType — HealthO Pro\n" . str_repeat('-', 44) . "\n";
    $plain .= "Name: $fullName\n";
    $plain .= "Email: $email\n";
    $plain .= "Mobile Number: $phone\n";
    if ($orgType !== '')  $plain .= "Organization / Type: $orgType\n";
    if ($interest !== '') $plain .= "Interested In / Position: $interest\n";
    if ($resume !== '')   $plain .= "Resume / CV: $resume\n";
    $plain .= "Location (auto-detected): $location\n";
    $plain .= str_repeat('-', 44) . "\n";
    $plain .= "Message:\n" . ($message !== '' ? $message : '— No message provided —') . "\n";

    $mail->Body    = $html;
    $mail->AltBody = $plain;

    $mail->send();
    http_response_code(200);
    echo json_encode(['status' => 'success', 'message' => 'Your message has been sent successfully.']);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => "Could not send your message. Please email digicarelynx@gmail.com directly."]);
}
?>
