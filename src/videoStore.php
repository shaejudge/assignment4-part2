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
		var_dump($_POST);
		//die;	
	
		//ini_set('display_errors', 'On');
		//ini_set('display_startup_errors', 'On');
		//error_reporting(E_ALL);
		
		
		$mysqli = new mysqli("oniddb.cws.oregonstate.edu", "judges-db", "6rmoErrYBdGgdSmP", "judges-db");
		if ($mysqli->connect_errno) {
			echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
		}else{
			echo "Connection Worked!<br>";
		}

	if(isset($_POST['Add'])){
	
		$sql = "INSERT INTO Video_Store(name, category, length) VALUES (?, ?, ?)";
		$stmt = $mysqli->prepare($sql);
		$stmt->bind_param("ssi", $name, $category, $length);
		
		$name = $_POST['videoName'];
		$category = $_POST['videoCat'];
		$length = $_POST['videoLen'];
		$stmt->execute();
		
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
				
		$stmt->close();
		$mysqli->close();	
	}
	
	
?>
	
	<h3>Video Database</h3>
	<table style="width:50%" border="1">
		<tr>
			<th>Name</th>
			<th>Category</th> 
			<th>Length</th>
			<th>Availability</th>
			<th><label for="ch-in">Check-in</label></th>
			<th><label for="ch-out">Check-out</label></th>
			<th>Delete</th>
		</tr>
		<tr>
		<?php
			
			$sql = "SELECT id, name, category, length, rented FROM Video_Store";
			$result = $mysqli->query($sql);
				
				if(isset($_POST['Delete'])){
					$db_name = $_POST['db_name'];
					$sql = "DELETE FROM `Video_Store` WHERE `name`='$db_name'";
				}
				
				if(isset($_POST['deleteAll'])){
					mysql_query("DELETE FROM Video_Store");
				}
				
				
				
				if(isset($_POST['subCheck'])){
					$db_name = $_POST['db_name'];
					$check = $_POST['check'];
					
					if($check == "Check-in"){
					$sql = "UPDATE Video_Store SET rented=0 WHERE name='$db_name'";
					}
					
					if($check == "Check-out"){
					$sql = "UPDATE Video_Store SET rented=1 WHERE name='$db_name'";
					}
				}
				
				if($row["rented"] == 0){
					$available = "Available!";
				}else{
					$available = "Checked out";
				}
				
				while($row = $result->fetch_assoc()) {
					echo '<form action="videoStore.php" method="POST">';
					echo '<td style="display:none;"><input type="text" name=db_id value="' . $row["id"]. '"></td>';
					echo '<td><input type="text" name=db_name value="' . $row["name"]. '" readonly></td>';
					echo "<td>" . $row["category"]. "</td>";
					echo "<td>" . $row["length"]. "</td>";
					echo "<td align='center'>" . $available . "</td>";
					echo '<td align="center"><input type="radio" name="check" id="ch-in" value="Check-in" /></td>';
					echo '<td align="center"><input type="radio" name="check" id="ch-out" value="Check-out" /></td>';
					echo '<td align="center"><input type="submit" value="Delete" name="Delete"></td>';
					echo "</form>";
					echo '</tr>';
				}	
		?>
					<td colspan="7"  align="center"><input type="submit" value="Submit Check-in/out" name="subCheck">
					</form>
					</td>
	</table>
					<br>
					<form action="videoStore.php" method="POST">
					<input type="submit" value="Delete All" name="deleteAll">
					</form>
					<br>
					
	

	</body>
</html>