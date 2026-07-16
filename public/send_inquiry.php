<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $to = $_POST['target_email'];
    $visitor_email = filter_var($_POST['visitor_email'], FILTER_SANITIZE_EMAIL);
    $visitor_name = htmlspecialchars($_POST['visitor_name']);
    $visitor_message = htmlspecialchars($_POST['message']);

    $subject = "New Portfolio Inquiry from: $visitor_name";
    
    $headers = "From: inquiries@capturra.com\r\n";
    $headers .= "Reply-To: $visitor_email\r\n";
    $headers .= "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: text/html; charset=UTF-8\r\n";

    $email_content = "
    <html>
    <body style='font-family: sans-serif; line-height: 1.6;'>
        <h2>You have a new inquiry!</h2>
        <p><strong>From:</strong> $visitor_name ($visitor_email)</p>
        <p><strong>Message:</strong></p>
        <p>$visitor_message</p>
    </body>
    </html>";

    if (mail($to, $subject, $email_content, $headers)) {
        header("Location: " . $_SERVER['HTTP_REFERER'] . "&status=success");
    } else {
        header("Location: " . $_SERVER['HTTP_REFERER'] . "&status=error");
    }
}