<html>
	<head>
	<title>Video Store</title>
	<body>
	
	
	<h1>Online Video Store</h1>

	<h3>Add Video</h3>	
	
	<form action="videoStore.php" method="POST">
		Name:<br>
		<input type="text" name="videoName" />
		<br>
		Category:<br>
		<input type="text" name="videoCat" />
		<br>
		Length (in minutes):<br>
		<input type="number" name="videoLen" />
		<br>
		<input type="submit" value="Add" name="Add">
	</form>
	<br>
	<br>
	
<?php 
		//var_dump($_POST);
		//die;	
	
		//ini_set('display_errors', 'On');
		//ini_set('display_startup_errors', 'On');
		//error_reporting(E_ALL);
		
		
		$mysqli = new mysqli("oniddb.cws.oregonstate.edu", "judges-db", "6rmoErrYBdGgdSmP", "judges-db");
		if ($mysqli->connect_errno) {
			echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
		}else{
			//echo "Connection Worked!<br>";
		}

	if(isset($_POST['Add'])){
		$mysqli = new mysqli("oniddb.cws.oregonstate.edu", "judges-db", "6rmoErrYBdGgdSmP", "judges-db");
		
		$sql = "INSERT INTO Video_Store(name, category, length) VALUES (?, ?, ?)";
		$stmt = $mysqli->prepare($sql);
		$stmt->bind_param("ssi", $name, $category, $length);
		
		$name = $_POST['videoName'];
		$category = $_POST['videoCat'];
		$length = $_POST['videoLen'];
		$stmt->execute();
		
		/*
		if (!($stmt = $mysqli->prepare($sql))) {
			echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
		}else{
			echo "Prepared!";
		}
		
		if (!$stmt->bind_param("ssi", $name, $category, $length)) {
			echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
		}else {
			echo "Bound!";
		}

		if (!$stmt->execute()) {
			echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
		}else{
			echo "Executed!";
		}
		*/
				
		$stmt->close();
		$mysqli->close();	
	}
	
	
?>
	
	<select id="cd" name="cd">
        
        <?php
            $mysqli = new mysqli("oniddb.cws.oregonstate.edu", "judges-db", "6rmoErrYBdGgdSmP", "judges-db");
            
			$query = "SELECT category FROM Video_Store";
            $result = mysql_query($query);
            
            while ($cdrow = mysql_fetch_array($result)) {
            $droplist = $droplistshow["categories"];
                echo "<option>" . $categories . "</option>";
            }
                
        ?>
    
        </select>
	
	<h3>Video Database</h3>
	<table style="width:50%" border="1">
		<tr>
			<th>Name</th>
			<th>Category</th> 
			<th>Length</th>
			<th>Availability</th>
			<th><label for="ch-in">Check-in</label></th>
			<th><label for="ch-out">Check-out</label></th>
			<th>Check In/Out</th>
			<th>Delete</th>
		</tr>
		<tr>
		<?php
			$mysqli = new mysqli("oniddb.cws.oregonstate.edu", "judges-db", "6rmoErrYBdGgdSmP", "judges-db");
			$query = "SELECT id, name, category, length, rented FROM Video_Store";
			$result = $mysqli->query($query);
				
				
				if(isset($_POST['Delete'])){
					$mysqli = new mysqli("oniddb.cws.oregonstate.edu", "judges-db", "6rmoErrYBdGgdSmP", "judges-db");
					
					$stmt = $mysqli->prepare("DELETE FROM Video_Store WHERE name = ?");
					//var_dump($dbName);
					$stmt->bind_param('s', $_POST['dbName']);
					$stmt->execute(); 
					$stmt->close();
				}
				
				if(isset($_POST['deleteAll'])){
					$mysqli = new mysqli("oniddb.cws.oregonstate.edu", "judges-db", "6rmoErrYBdGgdSmP", "judges-db");
					
					$stmt = $mysqli->prepare("DELETE FROM Video_Store");
					$stmt->bind_param();
					$stmt->execute(); 
					$stmt->close();
				}
				
				
				
				if(isset($_POST['subCheck'])){
					$mysqli = new mysqli("oniddb.cws.oregonstate.edu", "judges-db", "6rmoErrYBdGgdSmP", "judges-db");
					
					$dbName = $_POST['dbName'];
					
					
					if($_POST['check'] == "checkIn"){
						$stmt = $mysqli->prepare("UPDATE Video_Store SET rented = ? WHERE name = ?");
						$zero = 0;
						$stmt->bind_param('is', $zero, $dbName);
						$stmt->execute(); 
						$stmt->close();
					}
					
					if($_POST['check'] == "checkOut"){
						$stmt = $mysqli->prepare("UPDATE Video_Store SET rented = ? WHERE name = ?");
						$one = 1;
						$stmt->bind_param('is', $one, $dbName);
						$stmt->execute(); 
						$stmt->close();
					}
				}
				

				
				while($row = $result->fetch_array()) {
					
					if($row["rented"] == 0){
						$available = "Available!";
						$checked = "checked";
					}else{
						$available = "Checked out";
						$checked = "checked";
					}
					
					echo '<form action="videoStore.php" method="POST">';
					echo '<td style="display:none;"><input type="text" name=db_id value="' . $row["id"]. '"></td>';
					echo '<td><input type="text" name=dbName value="' . $row["name"]. '" readonly></td>';
					echo "<td>" . $row["category"]. "</td>";
					echo "<td>" . $row["length"]. "</td>";
					echo "<td align='center'>" . $available . "</td>";
					echo '<td align="center"><input type="radio" name="check" id="ch-in" value="checkIn"/></td>';
					echo '<td align="center"><input type="radio" name="check" id="ch-out" value="checkOut"/></td>';
					echo '<td align="center"><input type="submit" value="Submit" name="subCheck"></td>';
					echo '<td align="center"><input type="submit" value="Delete" name="Delete"></td>';
					echo "</form>";
					echo '</tr>';
				}	
		?>
					
					</form>
					
	</table>
					<br>
					<form action="" method="POST">
					<input type="submit" value="Delete All" name="deleteAll">
					</form>
		
					<form action="" method="POST">
					<input type="submit" value="Save All Changes" name="Save">
					</form>
					<br>
					
	

	</body>
</html>