<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $log_file = 'feedback.log';

    // Extract feedback type and description from form data
    $feedback_data = explode("\n", $_POST['feedback']);
    $feedback_type = str_replace('Feedback Type: ', '', $feedback_data[1]) ?? 'Unknown';
    $description = str_replace('Description: ', '', $feedback_data[2]) ?? '';

    // Extract email address from form data
    $email = explode("\n", $_POST['feedback'])[0] ?? 'N/A';
    $email = str_replace('Email Address: ', '', $email);

    // Set up authentication for sending email
    $username = 'your_email@example.com';
    $password = '${{ secrets.APP_PASSWORD }}';
    $headers = 'From: your_email@example.com' . "\r\n" .
        'Reply-To: dawgz9295@gmail.com' . "\r\n" .
        'X-Mailer: PHP/' . phpversion();

    // Sanitize and format input data
    $feedbackData = [
        'Timestamp' => date('Y-m-d H:i:s'),
        'Email' => $email,
        'Feedback Type' => $feedback_type,
        'Description' => $description,
    ];

    // Write formatted data to the log file
    $log_entry = json_encode($feedbackData, JSON_PRETTY_PRINT) . PHP_EOL;
    file_put_contents($log_file, $log_entry, FILE_APPEND);

    // Send email
    if (mail($username, 'Feedback Log', $log_entry, $headers, '-f' . $username, '-r' . $username . ' -S smtp.gmail.com:587 -S smtp-auth=login -S smtp-user=' . $username . ' -S smtp-pwd=' . $password)) {
        error_log("Sent feedback log to $username", 0);
    } else {
        error_log("Failed to send feedback log to $username", 0);
    }
}
?>
