<?php
session_start();
unset($_SESSION['username']);


	$returnURL = $_GET['returnURL'];
	if (isset($returnURL)) {
		header('Location: /dashboard/?returnURL='.$returnURL);
	}
else {
	header('Location: /dashboard/');
}


?>
