<?php  //Start the Session
session_start();
 require('../connect.php');
?>
   <html>
    <head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="/dashboard/css/todo.css" />
    <link href="https://fonts.googleapis.com/css?family=Quicksand:400,700" rel="stylesheet">
      <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css">
	<link rel="stylesheet" href="/dashboard/css/spectrum.css">
<script
  src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"
  integrity="sha256-VazP97ZCwtekAsvgPBSUwPFKdrwD3unUfSGVYrahUqU="
  crossorigin="anonymous"></script>
<script src="/dashboard/js/spectrum.js"></script>
<script src="/dashboard/js/ckeditor/ckeditor.js"></script>
<script src="/dashboard/js/highlight.js"></script>
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
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
<![endif]-->
<script src="../js/ckeditor/ckeditor.js"></script>
 <script>
	 $(document).ready(function() {
		 var d = new Date();
	var month = d.getMonth()+1;
	var day = d.getDate();
	var finalCurrentDate = d.getFullYear() + '-' +(month<10 ? '0' : '') + month + '-' + (day<10 ? '0' : '') + day;
	
	setTimeout(function(){
	if (navigator.userAgent.indexOf("Firefox") > 0) {
                $('input[type=datetime-local],input[type=date]').datepicker({
                  dateFormat : 'yy-mm-dd'
    });
		
				
     }
	else {
		$("#requestDueDate").val(finalCurrentDate+"T16:30");
		
	}
	}, 1);
		 $("#pending").css('opacity', 0).slideDown('slow').animate(
				{ opacity: 1 },
				{ queue: false, duration: 'slow' }
			  );
		 setTimeout(function(){
			if (navigator.userAgent.indexOf("Firefox") > 0) {
						$('input[type=datetime-local],input[type=date]').datepicker({
						  dateFormat : 'yy-mm-dd'
						});
			}else {}
			}, 1);		
	 CKEDITOR.replace('requestCopy');
	CKEDITOR.config.basicEntities = false;
	});
	</script>
    <style>
		 
		body {    background-image: linear-gradient(-225deg, #AC32E4 0%, #7918F2 48%, #4801FF 100%);}
	
		.leftField {
			    float: left;
    width: 49.5%;
			margin-right:1%;
		}
		.rightField {
			    float: right;
    width: 49.5%;
			
		}
		
		
		</style>
      </head>

    <body>
    

<div class="container">
<br><br>
	<div class="whitebg min_height" id="pending" style="
margin:0 auto;
    width: 60%;margin-top:5%;
">
    	 			<div class="header">
					<center><h1>Request Form</h1>
					<a href="check-status.php"><em>Check Status of A Ticket</em>&nbsp;&nbsp;<i class="fa fa-arrow-circle-right" aria-hidden="true"></i></a>&nbsp;<br><br></center>
					</div>
 	 		 
 	 		 		<center>
 	 		 		<form id="requestform" name="requestform" method="post" action="thankyou.php">
						<input type="text" id="requestContactName" required placeholder="Name" name="requestContactName" class="leftField">
						<input type="text" id="requestContactEmail" required placeholder="Email" name="requestContactEmail" class="rightField">
						<input type="text" id="requestURL" placeholder="URL" name="requestURL" class="leftField">
						<input type="datetime-local" id="requestDueDate" required name="requestDueDate" class="rightField">
						<div class="leftField">			
						<p>Title:</p>
							<input type="text" id="requestTitle" required name="requestTitle">
						</div>
						
						<div class="rightField">
						<p>Category:</p>
						<?php
					
					$getCategories = "SELECT * FROM `Team Projects Categories`";
					$getCategories_result = mysqli_query($connection, $getCategories) or die ("Query to get data from Team task failed: ".mysql_error());

							echo '<select name="requestCategory" id="requestCategory" style="width:100%"><option value="">Select</option>'; // Open your drop down box

							// Loop through the query results, outputing the options one by one
							while ($row = mysqli_fetch_array($getCategories_result)) {
							echo "<option value='" . $row['ProjectCategoryID'] ."'>" . $row['Category'] ."</option>";
							}

							echo '</select>';

					?>	
					</div>	
					
						<p>Description:</p>
						<textarea id="requestDescription" name="requestDescription"></textarea>
						<p>Copy:</p>
						<textarea style="height:300px;" id="requestCopy" placeholder="Copy" name="requestCopy">
							<h3><span style="color:#1abc9c"><strong>MAIN HERO CONTENT</strong></span></h3>

<p><strong>TITLE: </strong><span style="color:null">Enter title here.</span></p>

<p><span style="color:#9b59b6"><strong>COPY: </strong></span><span style="color:null">Enter copy here.</span></p>

<p><strong><span style="color:#2980b9">PRICING(s):</span> </strong><span style="color:null">Enter title here.</span></p>

<p><strong><span style="color:#3498db">LINK(s):</span> </strong><span style="color:null">Enter link(s) here.</span><strong> </strong></p>

<p><strong><span style="color:#e67e22">IMAGE REQUEST(s):</span> </strong><span style="color:null">Enter image request(s) here.</span></p>

<hr />
<h3><span style="color:#1abc9c"><strong>SECONDARY CONTENT</strong></span></h3>

<ul>
	<li><span style="color:#e74c3c"><strong>BANNER 1</strong></span>

	<ul>
		<li>
		<p><strong>BANNER TITLE: </strong><span style="color:null">Enter title here.</span><strong> </strong></p>

		<p><strong><span style="color:#3498db">CTA 1:</span> </strong><span style="color:null">Enter cta here.</span><strong> </strong></p>

		<p><strong><span style="color:#3498db">LINK 1:</span> </strong><span style="color:null">Enter link here.</span><strong> </strong></p>

		<p><strong><span style="color:#e67e22">IMAGE REQUEST:</span> </strong>Enter image request here.</p>

		<p><strong><span style="color:null">PRICING:</span> </strong><span style="color:null">Enter pricing information here.</span><strong> </strong></p>
		</li>
	</ul>
	</li>
	<li><span style="color:#e74c3c"><strong>BANNER 2</strong></span>
	<ul>
		<li>
		<p><strong>BANNER TITLE: </strong><span style="color:null">Enter title here.</span><strong> </strong></p>

		<p><strong><span style="color:#3498db">CTA 1:</span> </strong><span style="color:null">Enter cta here.</span><strong> </strong></p>

		<p><strong><span style="color:#3498db">LINK 1:</span> </strong><span style="color:null">Enter link here.</span><strong> </strong></p>

		<p><strong><span style="color:#e67e22">IMAGE REQUEST:</span> </strong>Enter image request here.</p>

		<p><strong><span style="color:null">PRICING:</span> </strong><span style="color:null">Enter pricing information here.</span></p>
		</li>
	</ul>
	</li>
</ul>

<hr />
<h3><span style="color:#1abc9c"><strong>MOBILE CONTENT</strong></span></h3>

<p><strong><span style="color:#3498db">CTA 1:</span> </strong><span style="color:null">Enter cta here.</span><strong> </strong></p>

<p><strong><span style="color:#3498db">LINK 1:</span> </strong><span style="color:null">Enter link here.</span></p>

						</textarea>
						<br>
							  
						<button id="sendRequest" class="genericbtn">Submit</button>		
					</form>
	 		 		
 	 		 		</center>	
  	 		 <br>&nbsp;
   	 		  </div>
</div>    

        
         
           
 

    </body>
</html>
<?php  ?>