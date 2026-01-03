<?php
session_start();
session_destroy();
header("Location: validar_login.php");
exit;
?>