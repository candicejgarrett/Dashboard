<?php // output headers so that the file is downloaded rather than displayed
require('../connect.php');
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=data.csv');

// create a file pointer connected to the output stream
$output = fopen('php://output', 'w');

// output the column headings
fputcsv($output, array('Title', 'Start Date', 'End Date', 'Category', 'Description'));

// fetch the data
$query = "SELECT `title`, `startdate`, `enddate`, `Category`, `Description` FROM `calendar`";  
      $result = mysqli_query($connection, $query);  
      while($row = mysqli_fetch_assoc($result))  
      {  
           fputcsv($output, $row);  
      }  
      fclose($output);  

?>