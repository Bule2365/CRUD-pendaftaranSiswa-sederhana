<?php
session_start();
session_destroy();
header("Location: login.php");
echo "<script>alert('Anda telah logout!'); window.location='login.php';</script>";
exit;
?>