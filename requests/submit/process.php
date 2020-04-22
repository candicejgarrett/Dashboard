<?php
require( '../../connect.php' );
require( '../../header.php' );
require( '../../emailDependents.php' );
include( '../../functions/global.php' );

$type = $_POST[ 'type' ];
$ticketID = $_POST[ 'ticketID' ];

if ( $type == "submitTicket" ) {
  $title = $_POST[ 'title' ];
  $url = $_POST[ 'url' ];
  $description = $_POST[ 'description' ];
  $copy = $_POST[ 'copy' ];
  $dueDate = date( "Y-m-d H:i:s", strtotime( $_POST[ 'dueDate' ] ) );
  $categoryID = $_POST[ 'categoryID' ];

  $ticketID = addTicket( $title, $url, $description, $dueDate, $categoryID, $copy );


  //////////////

  $result = [ "ticketID" => $ticketID ];
  header( 'Content-Type: application/json' );
  echo json_encode( $result );
}


?>