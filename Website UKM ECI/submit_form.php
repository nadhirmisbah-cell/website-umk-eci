<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 0);

// Set response header
header('Content-Type: application/json');

// Check if form was submitted via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data
    $name = isset($_POST['name']) ? trim($_POST['name']) : '';
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $message = isset($_POST['message']) ? trim($_POST['message']) : '';
    
    // Initialize response
    $response = array();
    
    // Validate input
    if (empty($name)) {
        $response['success'] = false;
        $response['message'] = 'Name is required.';
        echo json_encode($response);
        exit;
    }
    
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $response['success'] = false;
        $response['message'] = 'Valid email is required.';
        echo json_encode($response);
        exit;
    }
    
    if (empty($message)) {
        $response['success'] = false;
        $response['message'] = 'Message is required.';
        echo json_encode($response);
        exit;
    }
    
    // Sanitize input
    $name = htmlspecialchars($name);
    $email = htmlspecialchars($email);
    $message = htmlspecialchars($message);
    
    // Log the submission to a file
    $submission = array(
        'timestamp' => date('Y-m-d H:i:s'),
        'name' => $name,
        'email' => $email,
        'message' => $message
    );
    
    $filename = 'submissions.json';
    $submissions = array();
    
    // Read existing submissions
    if (file_exists($filename)) {
        $json_data = file_get_contents($filename);
        $submissions = json_decode($json_data, true);
        if (!is_array($submissions)) {
            $submissions = array();
        }
    }
    
    // Add new submission
    array_push($submissions, $submission);
    
    // Save to file
    if (file_put_contents($filename, json_encode($submissions, JSON_PRETTY_PRINT))) {
        // Send email notification
        $to = 'eciofikopin@gmail.com';
        $subject = 'New Join Request from ' . $name;
        $body = "Hello,\n\nYou have received a new join request:\n\n";
        $body .= "Name: " . $name . "\n";
        $body .= "Email: " . $email . "\n";
        $body .= "Message: " . $message . "\n";
        $body .= "Submitted at: " . date('Y-m-d H:i:s') . "\n";
        
        $headers = "From: " . $email . "\r\n";
        $headers .= "Reply-To: " . $email . "\r\n";
        $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";
        
        // Attempt to send email
        mail($to, $subject, $body, $headers);
        
        $response['success'] = true;
        $response['message'] = 'Thank you for your interest! We will contact you soon.';
    } else {
        $response['success'] = false;
        $response['message'] = 'Error saving your submission. Please try again.';
    }
    
    echo json_encode($response);
    exit;
} else {
    $response = array(
        'success' => false,
        'message' => 'Invalid request method.'
    );
    echo json_encode($response);
    exit;
}
?>
