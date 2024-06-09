<?php
session_start();
session_unset();
session_destroy();
header("Location: halaman_awal.php");
exit();
?>
