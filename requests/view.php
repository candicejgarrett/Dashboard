<?php 
$ticketID = $_GET['ticketID'];
if (isset($ticketID)){
	header("location:view/?ticketID=".$ticketID); 
}
else {
	header("location:view"); 
}


?>