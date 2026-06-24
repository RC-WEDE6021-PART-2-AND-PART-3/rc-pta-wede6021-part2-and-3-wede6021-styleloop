<?php
// logout.php: clears the current session and returns the user to the home page.
session_start();
session_destroy();
header("Location: index.php");
exit();
?>
