<?php
include_once( '../../header.php' );
require( '../../connect.php' );
$type = $_POST[ 'type' ];


if ( $type == 'updateStyleBandaid' ) {
  $code = addslashes( $_POST[ 'code' ] );
  $remove = "DELETE FROM `Code Bandaids` WHERE `Type` = 'style'";
  $remove_result = mysqli_query( $connection, $remove )or die( mysqli_error( $connection ) );

  $insert = "INSERT INTO `Code Bandaids`(`Code`,`Type`) VALUES ('$code','style')";
  $insert_result = mysqli_query( $connection, $insert )or die( mysqli_error( $connection ) );

}
if ( $type == 'updateScriptBandaid' ) {
  $code = addslashes( $_POST[ 'code' ] );
  $remove = "DELETE FROM `Code Bandaids` WHERE `Type` = 'script'";
  $remove_result = mysqli_query( $connection, $remove )or die( mysqli_error( $connection ) );

  $insert = "INSERT INTO `Code Bandaids`(`Code`,`Type`) VALUES ('$code','script')";
  $insert_result = mysqli_query( $connection, $insert )or die( mysqli_error( $connection ) );

}


?>