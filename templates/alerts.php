<?php


$todaysDate= date("Y-m-d h:i:s");
					$getAlerts = "SELECT * FROM `Homepage Alerts` WHERE `userID`='$userID' AND '$todaysDate' <= `Take Down` AND `seen`='0'";
					$getAlerts_result = mysqli_query($connection, $getAlerts) or die(mysqli_error($connection));
					echo "<div id='alertContainer'>";
					while($row = $getAlerts_result->fetch_assoc()) {
							$alertID = $row["AlertID"];
							$alertType = $row["Alert Type"];
							$alertTitle = $row["Alert Title"];
							$alertText = $row["Alert Text"];
						
							if ($alertType == "red") {
								echo '<div class="redAlert" alertid="'.$alertID.'"><i class="fa fa-times fa-2x pull-right" style="font-size:20px;color:#ffffff;cursor:pointer;" aria-hidden="true"></i><h1>'.$alertTitle.'</h1><p>'.$alertText.'</p></div>';
							}
							else if ($alertType == "green") {
								echo '<div class="greenAlert" alertid="'.$alertID.'"><i class="fa fa-times fa-2x pull-right" style="font-size:20px;color:#ffffff;cursor:pointer;" aria-hidden="true"></i><h1>'.$alertTitle.'</h1><p>'.$alertText.'</p></div>';
							}
					}
					echo "</div>";

?>