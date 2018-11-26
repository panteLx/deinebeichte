<?php
//include config
require_once('includes/config.php');
require_once('includes/config2.php');


$ip = get_client_ip();
	$checkip = mysqli_query($con,"SELECT * FROM Banned WHERE IP = '$ip'");
	$found = mysqli_num_rows($checkip);
	if ($found > 0){
		$row = mysqli_fetch_object($checkip);
		echo "<script language='javascript'>
  document.location='banned.php';</script>";
	}


	$SQLshow = mysqli_query($con,"SELECT Status FROM WhitelistMode");
	$row = mysqli_fetch_array($SQLshow);
		if ($row['Status'] == 'AKTIVIERT') {
	$checkip = mysqli_query($con,"SELECT * FROM Whitelist WHERE IP = '$ip'");
	$found = mysqli_num_rows($checkip);
	if (!$found > 0){
		$row = mysqli_fetch_object($checkip);
		echo "<script language='javascript'>
  document.location='./c.php?c=wartung';</script>";
	}
}


//check if already logged in move to home c
if( $user->is_logged_in() ){ header('Location: ./c.php?c=index'); exit(); }

//process login form if submitted
if(isset($_POST['submit'])){

	if (!isset($_POST['username'])) $error[] = "Bitte fülle alle Felder aus";
	if (!isset($_POST['password'])) $error[] = "Bitte fülle alle Felder aus";

	$username = $_POST['username'];
	if ( $user->isValidUsername($username)){
		if (!isset($_POST['password'])){
			$error[] = 'Ein Passwort muss angegeben werden!';
		}
		$password = $_POST['password'];
		if($user->login($username,$password)){

			$_SESSION['username'] = $username;
			$ip = get_client_ip();
			$details = json_decode(file_get_contents("http://ipinfo.io/{$ip}"));
			$location = $details->city.", ".$details->country;

			mysqli_query($con,"INSERT INTO AdminLogins (ID ,UserName ,IP ,Location ,Status ,LoginTime) VALUES (NULL,'$username', '$ip', '$location', 'SUCCESS', NOW())");
			mysqli_close($con);
			header('Location: ./c.php?c=index');
			exit;

		} else {
			$_SESSION['username'] = $username;
			$SQLshow = mysqli_query($con,"SELECT active FROM members WHERE username='$username'");
			$row = mysqli_fetch_array($SQLshow);
				if ($row['active'] == 'Disabled') {
					exit ("Dein Account wurde deaktiviert. Kontaktiere uns via E-Mail wenn es sich dabei um einen Fehler handelt - info@deinebeichte.com");
			}
			$error[] = 'Falsche Login Daten oder Account ist noch nicht aktiviert.';
			$_SESSION['username'] = $username;
			$ip = get_client_ip();
			$details = json_decode(file_get_contents("http://ipinfo.io/{$ip}"));
			$location = $details->city.", ".$details->country;

			mysqli_query($con,"INSERT INTO AdminLogins (ID ,UserName ,IP ,Location ,Status ,LoginTime) VALUES (NULL,'$username', '$ip', '$location', 'FAILED', NOW())");
			mysqli_close($con);
		}
	}else{
		$error[] = 'Username darf nur aus Alphanumerischen Zeichen bestehen und muss 3-16 Zeichen lang sein.';
	}



}//end if submit

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
		<meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login | Deinebeichte.com</title>
    <link href="//netdna.bootstrapcdn.com/bootstrap/3.1.0/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/main.css">
</head>
<body>
<div class="container">

	<div class="row">

	    <div class="col-xs-12 col-sm-8 col-md-6 col-sm-offset-2 col-md-offset-3">
			<form role="form" method="post" action="" autocomplete="off">
				<h2>Bitte logge dich ein</h2>
				<p><a href='register.php'>Noch kein Account? Registriere dich kostenlos!</a></p>
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
							echo "<h2 class='bg-success'>Dein Account wurde aktiviert! Du kannst dich jetzt einloggen.</h2>";
							break;
						case 'reset':
							echo "<h2 class='bg-success'>Bitte überprüfe deine E-Mails, ein Reset Link wurde dir zugeschickt.</h2>";
							break;
						case 'resetAccount':
							echo "<h2 class='bg-success'>Dein Passwort wurde geändert. Du kannst dich nun einloggen.</h2>";
							break;
							case 'joined':
								echo "<h2 class='bg-success'>Wir haben dir eine Bestätigungsemail geschickt. Bitte überprüfe dein Postfach!</h2>";
								break;
								case 'erroractivate':
									echo "<h2 class='bg-danger'>Dein Account konnte nicht aktiviert werden! Versuche es später erneut</h2>";
									break;
					}

				}


				?>

				<div class="form-group">
					<input type="text" name="username" id="username" class="form-control input-lg" placeholder="User Name" value="<?php if(isset($error)){ echo htmlspecialchars($_POST['username'], ENT_QUOTES); } ?>" tabindex="1">
				</div>

				<div class="form-group">
					<input type="password" name="password" id="password" class="form-control input-lg" placeholder="Passwort" tabindex="3">
				</div>

				<div class="row">
					<div class="col-xs-9 col-sm-9 col-md-9">
						 <a href='reset.php'>Passwort vergessen?</a>
					</div>
				</div>

				<hr>
				<div class="row">
					<div class="col-xs-6 col-md-6"><input type="submit" name="submit" value="Login" class="btn btn-primary btn-block btn-lg" tabindex="5"></div>
				</div>
			</form>
		</div>
	</div>



</div>
</body>
</html>
