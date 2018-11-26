<?php
require_once "./includes/recaptchalib.php";
// your secret key
$secret = "6LcaLX0UAAAAAJLLtxI1VI_NKljhZ3O71jMPuzoX";
// if submitted check response
// empty response
$response = null;

// check secret key
$reCaptcha = new ReCaptcha($secret);

if ($_POST["g-recaptcha-response"]) {
    $response = $reCaptcha->verifyResponse(
        $_SERVER["REMOTE_ADDR"],
        $_POST["g-recaptcha-response"]
    );
}



	date_default_timezone_set('Europe/Berlin'); //Change Here!


		$browser = new BrowserDetection();

		$userAgent       = $browser->getUserAgent();               //string
		$browserName     = $browser->getName();                    //string
		$browserVer      = $browser->getVersion();                 //string
		$platformFamily  = $browser->getPlatform();                //string
		$platformVer     = $browser->getPlatformVersion(true);     //string
		$platformName    = $browser->getPlatformVersion();         //string
		$platformIs64bit = $browser->is64bitPlatform();            //boolean
		$isMobile        = $browser->isMobile();                   //boolean
		$isRobot         = $browser->isRobot();                    //boolean
		$isInIECompat    = $browser->isInIECompatibilityView();    //boolean
		$strEmulatedIE   = $browser->getIECompatibilityView();     //string
		$arrayEmulatedIE = $browser->getIECompatibilityView(true); //array('browser' => '', 'version' => '')
		$isChromeFrame   = $browser->isChromeFrame();              //boolean
		$isAol           = $browser->isAol();                      //boolean
		$aolVer          = $browser->getAolVersion();              //string


		//Test a new user agent and output the instance of BrowserDetection as a string
		$browser->setUserAgent($_SERVER['HTTP_USER_AGENT']);

		 $browser1= "Browser: <b>".$browserName."</b> Browserversion: <b>".$browserVer."</b> Platform: <b>".$platformFamily."</b> Plattformversion: <b>".$platformName."</b>";//. " Browserversion" . $browserVer . " Platform " . $platformFamily . "  Platformversion " . $platformName;

	$wartung = mysqli_query($con,"SELECT * FROM Wartung WHERE Active = 'AKTIVIERT'");
	$foundwartung = mysqli_num_rows($wartung);
	if ($foundwartung > 0){
		$row = mysqli_fetch_object($wartung);
		//exit ("Wir führen gerade Wartungsarbeiten durch. In dieser Zeit ist unsere Webseite nicht erreichbar. In dringenden Fällen schreibe eine E-Mail an info@tsfree.de.");
	}

	/*
	INSERT DATA
	*/
	  if ($response != null && $response->success) {
	if (isset($_POST['create'])) {

		$msg = $_POST['beichte'];
		$msg1 = mysqli_real_escape_string($con, $msg);
		$browser2 = mysqli_real_escape_string($con, $browser1);

		$insert = "INSERT INTO Data (Message, IP, Public, Browser)
		VALUES ('$msg1', '$ip', 'FALSE', '$browser2')";
		if (!mysqli_query($con, $insert)) {
		echo "Insert Error: " .mysqli_error($con);
	}
 }
}
?>
<div class="card">
<div class="card-body">
    <h3 class="card-title box-title">Was liegt dir auf dem Herzen?</h3>
    <div class="card-content">

<?php if (isset($_POST['create']) && $response != null && $response->success) { ?>
<div class="alert alert-success" role="alert">
  <h4 class="alert-heading">Deine Beichte wurde erfolgreich gesendet!</h4>
  <p>Wir werden sie so schnell wie möglich auf Instagram sowie unserer Webseite posten. Deine Beichte wird in der Regel zuerst auf unserer Webseite gepostet.</p>
</div>
<?php } elseif ($foundwartung > 0) { ?>
<div class="alert alert-danger" role="alert">
  <h4 class="alert-heading">Wartungsarbeiten<h4>
  <p>Wir führen gerade Wartungsarbeiten durch. In dieser Zeit kannst du leider nur über <b><a target="_blank" href="https://www.instagram.com/deinebeichte_com">Instagram</a></b> beichten. In dringenden Fällen schreibe eine E-Mail an <b><a href="mailto:info@deinebeichte.com">info@deinebeichte.com</a></b>.</p>
</div>
<?php } elseif (!isset($_POST['create'])) { ?>

	<form method="POST">

  <div class="form-group">
    <label for="exampleFormControlTextarea1">Deine Beichte oder Kontaktanzeige</label>
    <textarea class="form-control" id="exampleFormControlTextarea1" rows="5" name="beichte" required></textarea>
  </div>
			<div class="g-recaptcha" data-sitekey="6LcaLX0UAAAAAPLsNjaewHn9dPpcpZEk1gJR9Mwi"></div><br>
				<button
            class="btn btn-primary btn-lg" name="create">Abschicken!
				</button>
	</form>
<?php } else { ?>
	<div class="alert alert-danger" role="alert">
	  <h4 class="alert-heading">Es ist ein Fehler aufgetreten<h4>
	  <p>Stelle sicher, dass du alle Felder ausgefüllt hast. Beachte auch das Google ReCaptcha.</p>
	</div>
<?php } ?>
</div>
</div>
</div>
