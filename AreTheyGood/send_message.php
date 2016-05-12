<?php include('includes/functions.php')?>

<?php
$message = $_POST ["queryMessage"];
$name = $_POST ["queryName"];

if (! isset ( $message ) || empty ( $message )) {
	return Alert ( AlertType::Error, "Please enter a message to send" );
	exit ();
}

$headers = "MIME-Version: 1.0" . "\r\n";
$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

$message = "<div>From: $name</div><br>" . $message;
mail ( "development@timdayley.com", "Message from Are they good...", $message, $headers );

return Alert ( AlertType::Success, "Message sent, thank you for the support" );
exit ();
?>