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

$resetToken = hash('SHA256', ($_GET['key']));

$stmt = $db->prepare('SELECT resetToken, resetComplete FROM members WHERE resetToken = :token');
$stmt->execute(array(':token' => $resetToken));
$row = $stmt->fetch(PDO::FETCH_ASSOC);

//if no token from db then kill the c
if(empty($row['resetToken'])){
	$stop = 'Falscher Aktivierungsschlüssel. Bitte überprüfe deine E-Mails oder kontaktiere uns.';
} elseif($row['resetComplete'] == 'Yes') {
	$stop = 'Dein Passwort wurde bereits geändert!';
}

//if form has been submitted process it
if(isset($_POST['submit'])){

	if (!isset($_POST['password']) || !isset($_POST['passwordConfirm']))
		$error[] = 'Beide Passwort Felder müssen ausgefüllt werden.';

	//basic validation
	if(strlen($_POST['password']) < 3){
		$error[] = 'Passwort ist zu kurz.';
	}

	if(strlen($_POST['passwordConfirm']) < 3){
		$error[] = 'Passwörter stimmen nicht überein.';
	}

	if($_POST['password'] != $_POST['passwordConfirm']){
		$error[] = 'Passwörter stimmen nicht überein.';
	}

	//if no errors have been created carry on
	if(!isset($error)){

		//hash the password
		$hashedpassword = $user->password_hash($_POST['password'], PASSWORD_BCRYPT);

		try {

			$stmt = $db->prepare("UPDATE members SET password = :hashedpassword, resetComplete = 'Yes'  WHERE resetToken = :token");
			$stmt->execute(array(
				':hashedpassword' => $hashedpassword,
				':token' => $row['resetToken']
			));

			//redirect to index c
			header('Location: login.php?action=resetAccount');
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
    <title>Passwort zurücksetzen | Anonymebeichten.de</title>
		<meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="//netdna.bootstrapcdn.com/bootstrap/3.1.0/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/main.css">
</head>
<body>
<div class="container">

	<div class="row">

	    <div class="col-xs-12 col-sm-8 col-md-6 col-sm-offset-2 col-md-offset-3">


	    	<?php if(isset($stop)){

	    		echo "<p class='bg-danger'>$stop</p>";

	    	} else { ?>

				<form role="form" method="post" action="" autocomplete="off">
					<h2>Ändere Passwort</h2>
					<hr>

					<?php
					//check for any errors
					if(isset($error)){
						foreach($error as $error){
							echo '<p class="bg-danger">'.$error.'</p>';
						}
					}

					//check the action
					switch ($_GET['action']) {
						case 'active':
							echo "<h2 class='bg-success'>Dein Account ist nun aktiviert. Du kannst dich jetzt anmelden.</h2>";
							break;
						case 'reset':
							echo "<h2 class='bg-success'>Bitte überprüfe deine E-Mails und klicke auf den Link zum zurücksetzen deines Passworts.</h2>";
							break;
					}
					?>

					<div class="row">
						<div class="col-xs-6 col-sm-6 col-md-6">
							<div class="form-group">
								<input type="password" name="password" id="password" class="form-control input-lg" placeholder="Passwort" tabindex="1">
							</div>
						</div>
						<div class="col-xs-6 col-sm-6 col-md-6">
							<div class="form-group">
								<input type="password" name="passwordConfirm" id="passwordConfirm" class="form-control input-lg" placeholder="Bestätige Passwort" tabindex="1">
							</div>
						</div>
					</div>

					<hr>
					<div class="row">
						<div class="col-xs-6 col-md-6"><input type="submit" name="submit" value="Passwort ändern" class="btn btn-primary btn-block btn-lg" tabindex="3"></div>
					</div>
				</form>

			<?php } ?>
		</div>
	</div>


</div>
</body>
</html>
