<?php

$errors = [];
$errorMessage = '';

$secret = '6LfhIF8rAAAAAFJIOeV0e-u7tl4ejyJZp6nwSQkG';

if (!empty($_POST)) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $subject = $_POST['subject'];
    $message = $_POST['message'];
    $recaptchaResponse = $_POST['g-recaptcha-response'];

    $recaptchaUrl = "https://www.google.com/recaptcha/api/siteverify?secret={$secret}&response={$recaptchaResponse}";
    $verify = json_decode(file_get_contents($recaptchaUrl));

    if (!$verify->success) {
        $errors[] = 'Recaptcha failed';
    }

    if (empty($name)) {
        $errors[] = 'Name is empty';
    }

    if (empty($email)) {
        $errors[] = 'Email is empty';
    } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Email is invalid';
    }

    if (empty($message)) {
        $errors[] = 'Message is empty';
    }

    if (!empty($errors)) {
        $allErrors = join('<br/>', $errors);
        $errorMessage = "<p style='color: red;'>{$allErrors}</p>";
    } else {
        $toEmail = 'hbo80sdb@gmail.com';
        $emailSubject = 'New email from your contact form';
        $headers = "From: {$email}\r\n";
        $headers .= "Reply-To: {$email}\r\n";
        $headers .= "Content-Type: text/html; charset=iso-8859-1\r\n";

        $body = nl2br("Name: {$name}\nEmail: {$email}\nSubject: {$subject}\nMessage:\n{$message}");

        if (mail($toEmail, $emailSubject, $body, $headers)) {
            header('Location: thank-you.html');
            exit;
        } else {
            $errorMessage = "<p style='color: red;'>Oops, something went wrong. Please try again later</p>";
        }
    }
}
?>
