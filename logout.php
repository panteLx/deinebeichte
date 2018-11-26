<?php require('includes/config.php');

//logout
$user->logout();

//logged in return to index c
header('Location: ./c.php?c=index');
exit;
?>
