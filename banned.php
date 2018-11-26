<?php
@include "includes/config.php";
@include "includes/config2.php";
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

    $sql = "SELECT * FROM Banned WHERE IP = '$ip'";
    $result = mysqli_query($con, $sql);
 ?>


  <!doctype html>
  <!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang=""> <![endif]-->
  <!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8" lang=""> <![endif]-->
  <!--[if IE 8]>         <html class="no-js lt-ie9" lang=""> <![endif]-->
  <!--[if gt IE 8]><!--> <html class="no-js" lang=""> <!--<![endif]-->
  <head>
      <meta charset="utf-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <title>Du wurdest ausgeschlossen! | Deinebeichte.com</title>
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

<link href="https://fonts.googleapis.com/css?family=Montserrat:300,700" rel="stylesheet">

<!-- Custom stlylesheet -->
<link type="text/css" rel="stylesheet" href="assets/404/style.css" />
</head>
<div class="card">
<div id="notfound">
  <div class="notfound">
    <?php while($row = mysqli_fetch_object($result)) { ?>
    <h2>Du wurdest vom Projekt ausgeschlossen!</h2>
    <p>Grund: <strong><?php echo $row->Reason; ?></strong></p>
    <br>
    <p> <small>Sollte es sich hierbei um einen Fehler handeln kontaktiere uns <br><strong> info@deinebeichte.com</p>
  </div>
</div>
</div>
<?php } ?>
