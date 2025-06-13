<?php

$errors = [];
$errorMessage = '';

if (!empty($_POST)) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $subject = $_POST['subject'];
    $message = $_POST['message'];

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

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Contact Form</title>
</head>
<body>
  <form action="action_page.php" method="post" id="contact-form">
    <h2>Contact Us</h2>

    <?php echo((!empty($errorMessage)) ? $errorMessage : '') ?>

    <p>
      <label>Name:</label><br>
      <input name="name" type="text">
    </p>
    <p>
      <label>Email Address:</label><br>
      <input name="email" type="text">
    </p>
    <p>
      <label>Subject:</label><br>
      <input name="subject" type="text">
    </p>
    <p>
      <label>Message:</label><br>
      <textarea name="message" rows="6" cols="40"></textarea>
    </p>
    <p>
      <button type="submit">Send</button>
    </p>
  </form>

  <!-- Optional client-side validation -->
  <script src="//cdnjs.cloudflare.com/ajax/libs/validate.js/0.13.1/validate.min.js"></script>
  <script>
    const constraints = {
        name: {
            presence: {allowEmpty: false}
        },
        email: {
            presence: {allowEmpty: false},
            email: true
        },
        subject: {
            presence: {allowEmpty: false}
        },
        message: {
            presence: {allowEmpty: false}
        }
    };

    const form = document.getElementById('contact-form');

    form.addEventListener('submit', function (event) {
        const formValues = {
            name: form.elements.name.value,
            email: form.elements.email.value,
            subject: form.elements.subject.value,
            message: form.elements.message.value
        };

        const errors = validate(formValues, constraints);

        if (errors) {
            event.preventDefault();
            const errorMessage = Object
                .values(errors)
                .map(fieldErrors => fieldErrors.join(', '))
                .join("\n");
            alert(errorMessage);
        }
    }, false);
  </script>
</body>
</html>
