<?php
@include "includes/config.php";
@include "includes/config2.php";
@include "includes/BrowserDetection.php";

//if(!$user->is_logged_in()){ header('Location: login.php'); exit(); }
// Sanitize $_GET parameters to avoid XSS and other attacks
// Databse Conn

if(!$user->is_logged_in()){
$AVAILABLE_PAGES = array('index','beichten','view','changelog','kontaktanzeigen','404','wartung','datenschutz','impressum','agb');
} elseif ($user->is_logged_in()){
$SQLshow = mysqli_query($con,"SELECT * FROM members WHERE username = '$_SESSION[username]'");
while($row = mysqli_fetch_array($SQLshow)){

if ($row["role"] == "Admin") {
$AVAILABLE_PAGES = array('index','blacklist','beichten','view','changelog','kontaktanzeigen','whitelist','logins','acpbeichten','dashboard','404','wartung','datenschutz','impressum','agb','users');
} elseif ($row["role"] == "Mod") {
$AVAILABLE_PAGES = array('index','beichten','view','changelog','kontaktanzeigen','acpbeichten','dashboard','404','wartung','datenschutz','impressum','agb');
} elseif ($row["role"] == "User") {
$AVAILABLE_PAGES = array('index','beichten','view','changelog','kontaktanzeigen','404','wartung','datenschutz','impressum','agb');
}
}
}
$AVAILABLE_PAGES = array_fill_keys($AVAILABLE_PAGES, 1);


$c = $_GET['c'];
if (!$AVAILABLE_PAGES[$c]) {
  echo "<script language='javascript'>
document.location='./c.php?c=404';</script>";
}

/*
IP BANNED CHECK
*/

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

$ip = get_client_ip();
	$checkip = mysqli_query($con,"SELECT * FROM Banned WHERE IP = '$ip'");
	$found = mysqli_num_rows($checkip);
	if ($found > 0){
		$row = mysqli_fetch_object($checkip);
    echo "<script language='javascript'>
  document.location='banned.php';</script>";
	}
	/*
	//IP BANNED CHECK
	*/
?>
<!doctype html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang=""> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8" lang=""> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9" lang=""> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang=""> <!--<![endif]-->
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <?php if ($c=='index'){ ?>
    <title>Anonym Beichten | Deinebeichte.com</title>
  <?php } elseif ($c=='view'){ ?>
    <title>Beichten & mehr | Deinebeichte.com</title>
  <?php } elseif ($c=='beichten'){ ?>
    <title>Beichten | Deinebeichte.com</title>
  <?php } elseif ($c=='kontaktanzeigen'){ ?>
    <title>Kontaktanzeigen | Deinebeichte.com</title>
  <?php } elseif ($c=='changelog'){ ?>
    <title>Changelogs | Deinebeichte.com</title>
  <?php } elseif ($c=='whitelist'){ ?>
    <title>Whitelist | Deinebeichte.com</title>
  <?php } elseif ($c=='blacklist'){ ?>
    <title>Bannliste | Deinebeichte.com</title>
  <?php } elseif ($c=='logins'){ ?>
    <title>Logins | Deinebeichte.com</title>
  <?php } elseif ($c=='acpbeichten'){ ?>
    <title>Beichten Verwalten | Deinebeichte.com</title>
  <?php } elseif ($c=='dashboard'){ ?>
    <title>Staff Dashboard | Deinebeichte.com</title>
  <?php } elseif ($c=='404'){ ?>
    <title>404 Not Found | Deinebeichte.com</title>
  <?php } elseif ($c=='wartung'){ ?>
    <title>Anmeldung deaktiviert | Deinebeichte.com</title>
  <?php } elseif ($c=='impressum'){ ?>
    <title>Impressum | Deinebeichte.com</title>
  <?php } elseif ($c=='datenschutz'){ ?>
    <title>Datenschutzerklärung | Deinebeichte.com</title>
  <?php } elseif ($c=='agb'){ ?>
    <title>Allgemeine Geschäftsbedingungen | Deinebeichte.com</title>
  <?php } elseif ($c=='users'){ ?>
    <title>Userverwaltung | Deinebeichte.com</title>
  <?php } ?>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" name="viewport" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
  <meta name="viewport" content="width=device-width, initial-scale=1"></meta>
  <meta name="author" content="deinebeichte.com">
  <meta name="publisher" content="deinebeichte.com">
  <meta name="copyright" content="deinebeichte.com">
  <meta name="description" content="Ließ spannende Beichten & Beichte selbst vollkommen anonym und kostenlos. ">
  <meta name="keywords" content="Beichte, Instagram, Kostenlos, Anonym, Beichten, Yourconfession, Anonymebeichten.de, Anonymebeichten, Kontakt, Kontaktanzeigen, Menschen, Anonyme, Beichten, Anonyme, Beichte">
  <meta name="page-topic" content="Gesellschaft">
  <meta name="audience" content="Alle"><meta http-equiv="content-language" content="de">
  <meta name="robots" content="index, follow">

    <link rel="apple-touch-icon" href="#">
    <link rel="shortcut icon" href="#">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/normalize.css@8.0.0/normalize.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/font-awesome@4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/lykmapipo/themify-icons@0.1.2/css/themify-icons.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/pixeden-stroke-7-icon@1.2.3/pe-icon-7-stroke/dist/pe-icon-7-stroke.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/flag-icon-css/3.2.0/css/flag-icon.min.css">
    <link rel="stylesheet" href="assets/css/cs-skin-elastic.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <!-- <script type="text/javascript" src="https://cdn.jsdelivr.net/html5shiv/3.7.3/html5shiv.min.js"></script> -->
    <link href="https://cdn.jsdelivr.net/npm/chartist@0.11.0/dist/chartist.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/jqvmap@1.5.1/dist/jqvmap.min.css" rel="stylesheet">
    <script src='https://www.google.com/recaptcha/api.js'></script>
</head>

<body>
    <!-- Left Panel -->
    <aside id="left-panel" class="left-panel">
        <nav class="navbar navbar-expand-sm navbar-default">
            <div id="main-menu" class="main-menu collapse navbar-collapse">
                <ul class="nav navbar-nav">
                  <li class="menu-title">Allgemein</li>
                    <li <?php if ($c=='index') { ?>class="active"<?php } ?>><a href="c.php?c=index"><i class="menu-icon fa fa-fort-awesome"></i>Beichte senden </a></li>
                    <li <?php if ($c=='beichten') { ?>class="active"<?php } ?>><a href="c.php?c=beichten"><i class="menu-icon fa fa-fort-awesome"></i>Beichten</a></li>
                    <li <?php if ($c=='kontaktanzeigen') { ?>class="active"<?php } ?>><a href="c.php?c=kontaktanzeigen"><i class="menu-icon fa fa-fort-awesome"></i>Kontaktanzeigen</a></li>
                    <li <?php if ($c=='changelog') { ?>class="active"<?php } ?>><a href="c.php?c=changelog"><i class="menu-icon fa fa-fort-awesome"></i>Changelogs</a></li>
                    <li class="menu-title">Mein Bereich</li>
                    <?php if($user->is_logged_in()){ ?>
                    <!-- <li <?php if ($c=='d') { ?>class="active"<?php } ?>><a href="index.html"><i class="menu-icon fa fa-fort-awesome"></i>Profil </a></li>
                    <li <?php if ($c=='d') { ?>class="active"<?php } ?>><a href="index.html"><i class="menu-icon fa fa-fort-awesome"></i>Einstellungen </a></li> -->
                    <li><a href="logout.php"><i class="menu-icon fa fa-fort-awesome"></i>Logout </a></li>
                  <?php } ?>
                    <?php if(!$user->is_logged_in()){ ?>
                    <li><a href="login.php"><i class="menu-icon fa fa-fort-awesome"></i>Login </a></li>
                    <li><a href="register.php"><i class="menu-icon fa fa-fort-awesome"></i>Registrierung </a></li>
                  <?php }
                  if($user->is_logged_in()){
                  $SQLshow = mysqli_query($con,"SELECT * FROM members WHERE username = '$_SESSION[username]'");
                  while($row = mysqli_fetch_array($SQLshow)){
                  if($row["role"] == "Mod" || $row["role"] == "Admin"){ ?>

                    <li class="menu-title">Staff Bereich</li><!-- /.menu-title -->

                          <li <?php if ($c=='dashboard') { ?>class="active"<?php } ?>><a href="c.php?c=dashboard"><i class="menu-icon fa fa-fort-awesome"></i>Dashboard</a></li>
                          <li <?php if ($c=='acpbeichten') { ?>class="active"<?php } ?>><a href="c.php?c=acpbeichten"><i class="menu-icon fa fa-fort-awesome"></i>Beichten Verwalten</a></li>
                          <?php if($row["role"] == "Admin"){ ?>
                          <li <?php if ($c=='logins') { ?>class="active"<?php } ?>><a href="c.php?c=logins"><i class="menu-icon fa fa-fort-awesome"></i>Logins</a></li>
                          <li <?php if ($c=='users') { ?>class="active"<?php } ?>><a href="c.php?c=users"><i class="menu-icon fa fa-fort-awesome"></i>Userverwaltung</a></li>
                          <li <?php if ($c=='blacklist') { ?>class="active"<?php } ?>><a href="c.php?c=blacklist"><i class="menu-icon fa fa-fort-awesome"></i>Bannliste</a></li>
                          <li <?php if ($c=='whitelist') { ?>class="active"<?php } ?>><a href="c.php?c=whitelist"><i class="menu-icon fa fa-fort-awesome"></i>Whitelist</a></li>
                        <?php } } } } ?>
                </ul>
            </div><!-- /.navbar-collapse -->
        </nav>
    </aside>
    <!-- /#left-panel -->
    <!-- Right Panel -->
    <div id="right-panel" class="right-panel">
        <!-- Header-->
        <header id="header" class="header">
            <div class="top-left">
              <div class="header-menu">
                    <p class="navbar-brand">Deinebeichte.com</p>
                    <a id="menuToggle" class="menutoggle"><i class="fa fa-bars"></i></a>

                </div>
            </div>
<style type="text/css">
/* Landscape phones and down */
@media (max-width: 600px) {
    .bigheader    {
      display: none;
    }
}

</style>
      <div class="top-right bigheader">
            <div class="header-menu">
                <div class="header-left">
                  <form method="post">
                    <a href="logout.php"><i class="fa fa-sign-out menutoggle" aria-hidden="true"></i></a>
                  </form>
                </div>
              </div>
            </div>
        </header>
        <!-- /#header -->
        <!-- Content -->
        <div class="content">
            <!-- Animated -->
            <div class="animated fadeIn">
              <?php
        if ($c=='index'){
          @include './c/index.php';
        } elseif ($c=='blacklist'){
          @include './c/blacklist.php';
        } elseif ($c=='beichten'){
          @include './c/beichten.php';
        } elseif ($c=='view'){
          @include './c/view.php';
        } elseif ($c=='changelog'){
          @include './c/changelog.php';
        } elseif ($c=='kontaktanzeigen'){
          @include './c/kontaktanzeigen.php';
        } elseif ($c=='whitelist'){
          @include './c/whitelist.php';
        } elseif ($c=='logins'){
          @include './c/logins.php';
        } elseif ($c=='acpbeichten'){
          @include './c/acpbeichten.php';
        } elseif ($c=='dashboard'){
          @include './c/dashboard.php';
        } elseif ($c=='404'){
          @include './c/404.php';
        } elseif ($c=='wartung'){
          @include './c/wartung.php';
        } elseif ($c=='impressum'){
          @include './c/impressum.php';
        } elseif ($c=='datenschutz'){
          @include './c/datenschutz.php';
        } elseif ($c=='agb'){
          @include './c/agb.php';
        } elseif ($c=='users'){
          @include './c/users.php';
        } elseif ($c==$search){
          @include $search;
        }
        ?>
            </div>
            <!-- .animated -->
        </div>
        <!-- /.content -->
        <div class="clearfix"></div>
        <!-- Footer -->
        <footer class="site-footer">
            <div class="footer-inner bg-white">
              <center>
                <a href="mailto:info@deinebeichte.com" target="_blank">Fehler melden</a> | <a href="mailto:abuse@deinebeichte.com" target="_blank">Missbrauch melden</a> | <a href="mailto:team@deinebeichte.com" target="_blank">Kontakt</a><br>
                <a href="c.php?c=agb" target="_blank">Allgemeine Geschäftsbedingungen</a> | <a href="c.php?c=datenschutz" target="_blank">Datenschutzerklärung</a> | <a href="c.php?c=impressum" target="_blank">Impressum</a>
              </center>
              <hr>

                <div class="row">
                    <div class="col-sm-6">
                        Copyright &copy; 2018 Deinebeichte.com - Developed with ♥ near Cologne
                    </div>
                    <div class="col-sm-6 text-right">
                      <?php
                        if (!$user->is_logged_in()) {
                          echo "Melde dich jetzt kostenlos an!";
                        } elseif ($user->is_logged_in()) {
                          echo $_SESSION['username'];
                        }
                       ?>

                    </div>
                </div>
            </div>
        </footer>
        <!-- /.site-footer -->
    </div>

    <!-- /#right-panel -->

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/jquery@2.2.4/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.4/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-match-height@0.7.2/dist/jquery.matchHeight.min.js"></script>
    <script src="assets/js/main.js"></script>

    <!--  Chart js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@2.7.3/dist/Chart.bundle.min.js"></script>

    <!--Chartist Chart-->
    <script src="https://cdn.jsdelivr.net/npm/chartist@0.11.0/dist/chartist.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartist-plugin-legend@0.6.2/chartist-plugin-legend.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/jquery.flot@0.8.3/jquery.flot.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flot-pie@1.0.0/src/jquery.flot.pie.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flot-spline@0.0.1/js/jquery.flot.spline.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/simpleweather@3.1.0/jquery.simpleWeather.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/moment@2.22.2/moment.min.js"></script>


    <!--Local Stuff-->

</body>
</html>
