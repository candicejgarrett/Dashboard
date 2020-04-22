<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Example: Browsing Files</title>
   
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css">
<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
    <script>
		$(document).ready(function() {
		
			function GetURLParameter(sParam)
		{
			var sPageURL = window.location.search.substring(1);
			var sURLVariables = sPageURL.split('&');
			for (var i = 0; i < sURLVariables.length; i++)
			{
				var sParameterName = sURLVariables[i].split('=');
				if (sParameterName[0] == sParam)
				{
					return sParameterName[1];
				}
			}
		}
			
			
		$(document).on("click",".images",function() {
			
			var fileName = $(this).attr("id");
			var funcNum = GetURLParameter( 'CKEditorFuncNum' );
			
            var fileUrl = 'uploads/'+fileName;
            window.opener.CKEDITOR.tools.callFunction( funcNum, fileUrl );
           window.close();
			
			});
		});
		
    </script>
    
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <style>
	  .images {width:60px; height:60px; display:inline-block;margin:10px;border:1px solid #eaeaea;font-size:1px;}
	  img {width:100%;}
	  </style>
</head>
<body>

<?php
							// Opens directory
							$myDirectory=opendir('../uploads/');
							$path = 'uploads/';
							// Gets each entry
							while($entryName=readdir($myDirectory)) {
							  $dirArray[]=$entryName;
							}

							// Finds extensions of files
							function findexts ($filename) {
							  $filename=strtolower($filename);
							  $exts=split("[/\\.]", $filename);
							  $n=count($exts)-1;
							  $exts=$exts[$n];
							  return $exts;
							}

							// Closes directory
							closedir($myDirectory);

							// Counts elements in array
							$indexCount=count($dirArray);

							// Sorts files
							sort($dirArray);

							// Loops through the array of files
							for($index=0; $index < $indexCount; $index++) {

							  // Allows ./?hidden to show hidden files
							  if($_SERVER['QUERY_STRING']=="hidden")
							  {$hide="";
							  $ahref="./";
							  $atext="Hide";}
							  else
							  {$hide=".";
							  $ahref="./?hidden";
							  $atext="Show";}
							  if(substr("$dirArray[$index]", 0, 1) != $hide) {

							  // Gets File Names
							  $name=$dirArray[$index];
							  $namehref=$dirArray[$index];

							  // Gets Extensions 
							  $extn=findexts($dirArray[$index]); 

							  // Gets file size 
							  //$size=number_format(filesize($dirArray[$index]));

							  // Gets Date Modified Data
							  //$modtime=date("M j Y g:i A", filemtime($dirArray[$index]));
							  //$timekey=date("YmdHis", filemtime($dirArray[$index]));

							  // Prettifies File Types, add more to suit your needs.
							  switch ($extn){
								case "png": $extn="PNG Image"; break;
								case "jpg": $extn="JPEG Image"; break;
								case "svg": $extn="SVG Image"; break;
								case "gif": $extn="GIF Image"; break;
								case "ico": $extn="Windows Icon"; break;

								case "txt": $extn="Text File"; break;
								case "log": $extn="Log File"; break;
								case "htm": $extn="HTML File"; break;
								case "php": $extn="PHP Script"; break;
								case "js": $extn="Javascript"; break;
								case "css": $extn="Stylesheet"; break;
								case "pdf": $extn="PDF Document"; break;

								case "zip": $extn="ZIP Archive"; break;
								case "bak": $extn="Backup File"; break;

								default: $extn=strtoupper($extn)." File"; break;
							  }

							  // Separates directories
							  if(is_dir($dirArray[$index])) {
								$extn="&lt;Directory&gt;"; 
								$size="&lt;Directory&gt;"; 
								$class="dir";
							  } else {
								$class="printActualFiles";
							  }

							  // Cleans up . and .. directories 
							  if($name=="."){$name=". (Current Directory)"; $extn="&lt;System Dir&gt;";}
							  if($name==".."){$name=".. (Parent Directory)"; $extn="&lt;System Dir&gt;";}

							  // Print 'em
							  print("
							  <div class='images' style='background: url(../$path$name) no-repeat center center fixed; -webkit-background-size: cover;-moz-background-size: cover; -o-background-size: cover;background-size: cover;' id='$name'></div>
							");
							  }
							}
						  ?>

</body>
</html>