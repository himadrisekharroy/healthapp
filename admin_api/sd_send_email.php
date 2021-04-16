<?php
function sendMail($user_email,$msg,$subject_line)
{

$to = $user_email; // note the comma

// Subject
$subject = $subject_line;

// Message
$message = '
<html>
<head>
  <title>'.$subject_line.'</title>
</head>
<body>
  <p><b>'.$msg.'</b></p>
  <p><b>Regards,<br/><br/> STREET DELIGHT TEAM.</b></p>
</body>
</html>
';

// To send HTML mail, the Content-type header must be set
$headers[] = 'MIME-Version: 1.0';
$headers[] = 'Content-type: text/html; charset=iso-8859-1';

// Additional headers
$headers[] = 'To: '.$user_email;
$headers[] = 'From: Street Delight <no-reply@streetdelight.com>';

// Mail it
mail($to, $subject, $message, implode("\r\n", $headers));
}
?>