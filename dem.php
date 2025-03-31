<?php
require_once __DIR__ . '/includes/email_functions.php';

// Enable verbose output
echo "<pre>";

$testEmail = 'amalvarghese113112@gmail.com';

try {
    $mail = getMailer();
    $mail->addAddress($testEmail);
    $mail->Subject = 'Test Email with Debug';
    $mail->Body = '<h1>Test Email</h1><p>Debugging email sending.</p>';
    $mail->AltBody = 'Test Email - Debugging email sending.';
    
    echo "Attempting to send email...\n";
    echo "SMTP Host: " . $mail->Host . "\n";
    echo "SMTP Username: " . $mail->Username . "\n";
    
    if ($mail->send()) {
        echo "Email sent successfully!";
    } else {
        echo "Email failed to send.\n";
        echo "Error Info: " . $mail->ErrorInfo;
    }
} catch (Exception $e) {
    echo "Exception occurred:\n";
    echo $e->getMessage() . "\n";
    echo "SMTP Error: " . ($mail->ErrorInfo ?? 'No additional info');
}

echo "</pre>";