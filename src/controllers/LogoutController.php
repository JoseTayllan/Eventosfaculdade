<?php
session_start();
session_unset();
session_destroy();
header("Location: /Eventosfaculdade/src/views/usuarios/login.php");
exit;
?>
