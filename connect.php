<?php
 if(!isset($_SESSION)) 
    { 
        session_start();
    } 

$connection = mysqli_connect('127.0.0.1', 'root', 'newpassword');
date_default_timezone_set('America/New_York');


if (!$connection){
    die("Database Connection Failed" . mysqli_error($connection));
	echo "failed db connection";
}
$select_db = mysqli_select_db($connection, 'dashboard');
if (!$select_db){
    die("Database Selection Failed" . mysqli_error($connection));
	echo "failed db connection";
}


//PDO
$servername = "127.0.0.1";
$serverusername = "root";
$serverpassword = "newpassword";
$dbname = "dashboard";



//styles and js links
$stylesjs = '<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="/dashboard/css/styles.css" />
	 <link rel="stylesheet" href="/dashboard/css/style.css" />
    <link href="https://fonts.googleapis.com/css?family=Quicksand:100,400,700" rel="stylesheet">
      <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
	  <link rel="stylesheet" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
   <script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
    <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css">
	<link rel="stylesheet" href="/dashboard/css/spectrum.css">
	<link rel="stylesheet" href="/dashboard/css/alertable.css">
<script
  src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"
  integrity="sha256-VazP97ZCwtekAsvgPBSUwPFKdrwD3unUfSGVYrahUqU="
  crossorigin="anonymous"></script>
  <link rel="stylesheet" href="/dashboard/css/jquery-ui-timepicker-addon.css">
  <script src="/dashboard/js/jquery-ui-timepicker-addon.js"></script>
<script src="/dashboard/js/spectrum.js"></script>
<script src="/dashboard/js/alertable.min.js"></script>
<script src="/dashboard/js/ckeditor/ckeditor.js"></script>
<script src="/dashboard/js/highlight.js"></script>
<script src="/dashboard/js/dragscrollable.min.js"></script>
<link rel="apple-touch-icon" sizes="57x57" href="/dashboard/images/favicon/apple-icon-57x57.png">
<link rel="apple-touch-icon" sizes="60x60" href="/dashboard/images/favicon/apple-icon-60x60.png">
<link rel="apple-touch-icon" sizes="72x72" href="/dashboard/images/favicon/apple-icon-72x72.png">
<link rel="apple-touch-icon" sizes="76x76" href="/dashboard/images/favicon/apple-icon-76x76.png">
<link rel="apple-touch-icon" sizes="114x114" href="/dashboard/images/favicon/apple-icon-114x114.png">
<link rel="apple-touch-icon" sizes="120x120" href="/dashboard/images/favicon/apple-icon-120x120.png">
<link rel="apple-touch-icon" sizes="144x144" href="/dashboard/images/favicon/apple-icon-144x144.png">
<link rel="apple-touch-icon" sizes="152x152" href="/dashboard/images/favicon/apple-icon-152x152.png">
<link rel="apple-touch-icon" sizes="180x180" href="/dashboard/images/favicon/apple-icon-180x180.png">
<link rel="icon" type="image/png" sizes="192x192"  href="/dashboard/images/favicon/android-icon-192x192.png">
<link rel="icon" type="image/png" sizes="32x32" href="/dashboard/images/favicon/favicon-32x32.png">
<link rel="icon" type="image/png" sizes="96x96" href="/dashboard/images/favicon/favicon-96x96.png">
<link rel="icon" type="image/png" sizes="16x16" href="/dashboard/images/favicon/favicon-16x16.png">
<link rel="shortcut icon" href="/dashboard/images/favicon/favicon.ico" >
<link rel="manifest" href="/dashboard/images/favicon/manifest.json">
<meta name="msapplication-TileColor" content="#ffffff">
<meta name="msapplication-TileImage" content="/dashboard/images/favicon/ms-icon-144x144.png">
<meta name="theme-color" content="#ffffff">
';

?>