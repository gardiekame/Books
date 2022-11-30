<?php
function connectDb($host, $user, $pwd, $dbName) {
	$conn = new mysqli($host, $user, $pwd, $dbName);

	if($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	} 
	
	echo 'MySQL Server connected<br>';	
	
	//$conn->query("SET NAMES 'utf8'"); 
	$conn->set_charset('utf8mb4');
	
	return $conn;
}

function addItem($id, $title, $genre, $publisher, $date, $img, $dbCon, $table) {
	$sql = "INSERT INTO $table (id, title, genre, publisher, release_date, img_url)
		VALUES ('$id', '$title' , '$genre', '$publisher', '$date', '$img')";

	if ($dbCon->query($sql) === TRUE) {
		echo "New record created successfully";
	} else {
		echo "Error: " .$sql ."<br>" .$dbCon->error;
	}
}

function updateItem($id, $title, $genre, $publisher, $date, $img, $dbCon, $table) {
	$sql = "UPDATE $table SET title='$title', genre='$genre', publisher='$publisher',
		release_date='$date', img_url='$img' WHERE id=$id";

	if ($dbCon->query($sql) === TRUE) {
		echo "New record updated successfully";
	}else {
		echo "Error: " .$sql ."<br>" .$dbCon->error;
	}
}

function updateItem2($id, $title, $genre, $publisher, $date, $dbCon, $table) {
	$sql = "UPDATE $table SET title='$title', genre='$genre', publisher='$publisher',
		release_date='$date' WHERE id=$id";

	if ($dbCon->query($sql) === TRUE) {
		echo "New record updated successfully";
	}else {
		echo "Error: " .$sql ."<br>" .$dbCon->error;
	}
}

function queryDb($sql, $dbCon) {
	$result = $dbCon->query($sql);
	
	if($result->num_rows > 0) {
		$i = 0;
		
		while($rs[$i] = $result->fetch_assoc()) {
			$i++;
		}
		
		array_pop($rs);
		
		return $rs;
	}
	else {
		echo $sql .": 查無資料";
	}
}

function counts($sql, $dbCon) {
	$result = $dbCon->query($sql)->fetch_row();
	
	return $result[0];
}
//$conn->close();
?>