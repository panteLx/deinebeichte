<?php require('includes/config.php');
require('includes/config2.php');

$ip = get_client_ip();
	$checkip = mysqli_query($con,"SELECT * FROM Banned WHERE IP = '$ip'");
	$found = mysqli_num_rows($checkip);
	if ($found > 0){
		$row = mysqli_fetch_object($checkip);
		echo "<script language='javascript'>
  document.location='banned.php';</script>";
	}

//if logged in redirect to members c
if( $user->is_logged_in() ){ header('Location: ./c.php?c=index'); exit(); }

//if form has been submitted process it
if(isset($_POST['submit'])){

	//Make sure all POSTS are declared
	if (!isset($_POST['email'])) $error[] = "Bitte fülle alle Felder aus.";


	//email validation
	if(!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)){
	    $error[] = 'Bitte gib eine gültige E-Mail an.';
	} else {
		$stmt = $db->prepare('SELECT email FROM members WHERE email = :email');
		$stmt->execute(array(':email' => $_POST['email']));
		$row = $stmt->fetch(PDO::FETCH_ASSOC);

		if(empty($row['email'])){
			$error[] = 'Es wurde kein Account mit der eingegeben E-Mail gefunden.';
		}

	}

	//if no errors have been created carry on
	if(!isset($error)){

		//create the activation code
		$stmt = $db->prepare('SELECT password, email FROM members WHERE email = :email');
		$stmt->execute(array(':email' => $_POST['email']));
		$row = $stmt->fetch(PDO::FETCH_ASSOC);
		$token = hash_hmac('SHA256', $user->generate_entropy(8), $row['password']);//Hash and Key the random data
        $storedToken = hash('SHA256', ($token));//Hash the key stored in the database, the normal value is sent to the user

		try {

			$stmt = $db->prepare("UPDATE members SET resetToken = :token, resetComplete='No' WHERE email = :email");
			$stmt->execute(array(
				':email' => $row['email'],
				':token' => $storedToken
			));

			//send email
			$to = $row['email'];
			$subject = "Dein Passwort Reset auf deinebeichte.com";
			$body = "<p>Jemand hat ein Reset deines Passworts angefordert.</p>
			<p>Falls dies ein versehen war ignoriere diese E-Mail oder kontaktiere uns via E-Mail.</p>
			<p>Um dein Passwort zurueckzusetzen klicke auf den folgenden Link: <a href='".DIR."resetPassword.php?key=$token'>".DIR."resetPassword.php?key=$token</a></p>
			<p>Hab einen tollen Tag </p>
			<p><br></p>
			<p>--------------</p>
			<p><strong>Bitte antworte nicht auf diese E-Mail.</strong></p>";

			$mail = new Mail();
			$mail->setFrom(SITEEMAIL);
			$mail->addAddress($to);
			$mail->subject($subject);
			$mail->body($body);
			$mail->send();

			//redirect to index c
			header('Location: login.php?action=reset');
			exit;

		//else catch the exception and show the error.
		} catch(PDOException $e) {
		    $error[] = $e->getMessage();
		}

	}

}

function get_client_ip() {
    $ipaddress = '';
    if (isset($_SERVER['HTTP_CLIENT_IP']))
        $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
    else if(isset($_SERVER['HTTP_X_FORWARDED_FOR']))
        $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
    else if(isset($_SERVER['HTTP_X_FORWARDED']))
        $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
    else if(isset($_SERVER['HTTP_FORWARDED_FOR']))
        $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
    else if(isset($_SERVER['HTTP_FORWARDED']))
        $ipaddress = $_SERVER['HTTP_FORWARDED'];
    else if(isset($_SERVER['REMOTE_ADDR']))
        $ipaddress = $_SERVER['REMOTE_ADDR'];
    else
        $ipaddress = 'UNKNOWN';
    return $ipaddress;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Passwort zurücksetzen | Deinebeichte.com</title>
		<meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="//netdna.bootstrapcdn.com/bootstrap/3.1.0/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/main.css">
</head>
<body>
<div class="container">

	<div class="row">

	    <div class="col-xs-12 col-sm-8 col-md-6 col-sm-offset-2 col-md-offset-3">
			<form role="form" method="post" action="" autocomplete="off">
				<h2>Reset Passwort</h2>
				<p><a href='login.php'>Zurück zur Login Seite</a></p>
				<hr>

				<?php
				//check for any errors
				if(isset($error)){
					foreach($error as $error){
						echo '<p class="bg-danger">'.$error.'</p>';
					}
				}

				if(isset($_GET['action'])){

					//check the action
					switch ($_GET['action']) {
						case 'active':
							echo "<h2 class='bg-success'>Dein Passwort wurde zurückgesetzt.</h2>";
							break;
						case 'reset':
							echo "<h2 class='bg-success'>Bitte überprüfe deine E-Mail um dein Passwort zurückzusetzen.</h2>";
							break;
					}
				}
				?>

				<div class="form-group">
					<input type="email" name="email" id="email" class="form-control input-lg" placeholder="Email" value="" tabindex="1">
				</div>

				<hr>
				<div class="row">
					<div class="col-xs-6 col-md-6"><input type="submit" name="submit" value="Passwort zurücksetzen" class="btn btn-primary btn-block btn-lg" tabindex="2"></div>
				</div>
			</form>
		</div>
	</div>


</div>
</body>
</html>
