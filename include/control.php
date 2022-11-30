<?php

$dbConn = connectDb($host, $user, $pwd, $dbName);

if(isset($_GET['searchTitle']) && $_GET['searchTitle'] !="") {
	$searchTitle = htmlspecialchars($_GET['searchTitle']);
	$toPass['searchTitle'] = $searchTitle;
}

isset($_GET['selGenre']) ? $selGenre = $_GET['selGenre'] : $selGenre = "全部";
isset($_GET['selPublisher']) ? $selPublisher = $_GET['selPublisher'] : $selPublisher = "全部";

$tables = array($itemTable, $genreTable, $publisherTable);
$toPass['selGenre'] = $selGenre;
$toPass['selPublisher'] = $selPublisher;

if($selGenre != "全部" && $selPublisher != "全部") {
	$sqlcmd = getRetrievalSql("searchGenre&Publisher", $toPass, $tables);
	$itemCounts = counts($sqlcmd[0], $dbConn);	
}
else if($selGenre != "全部" && $selPublisher == "全部"){
	$sqlcmd = getRetrievalSql("searchGenre", $toPass, $tables);
	$itemCounts = counts($sqlcmd[0], $dbConn);
}
else if($selGenre == "全部" && $selPublisher != "全部") {
	$sqlcmd = getRetrievalSql("searchPublisher", $toPass, $tables);
	$itemCounts = counts($sqlcmd[0], $dbConn);
}
else if(isset($searchTitle)) {
	$sqlcmd = getRetrievalSql("searchTitle", $toPass, $tables);
	$itemCounts = counts($sqlcmd[0], $dbConn);
}
else {
	$sqlcmd = getRetrievalSql("usual", $toPass, $tables);
	$itemCounts = counts($sqlcmd[0], $dbConn);
}

$totalPage = (int)ceil($itemCounts/$itemPerPage);

if(!isset($_GET['page']) || $_GET['page'] < 1 || $_GET['page'] > $totalPage)
	$page = 1;
else
	$page = $_GET['page'];
if(isset($_GET['lastPage']) && $_GET['page'] > 1 && $_GET['page'] <= $totalPage) 
	$page--;
if(isset($_GET['nextPage']) && $_GET['page'] > 0 && $_GET['page'] < $totalPage) 
	$page++;

$start = ($page - 1)*$itemPerPage;
$sqlCmd = $sqlcmd[1] ."LIMIT $start, $itemPerPage";

##---handle operation of adding/editing items----------------
if(isset($_POST['titleAdded']) && $_POST['titleAdded'] != "") {
	
	if($_POST['theOperation'] == "addItem") {
		$imageAdded = counts("SELECT MAX(id) FROM $itemTable", $dbConn) + 1;
		$itemId = (int)($imageAdded);
	}		
	else if($_POST['theOperation'] == "editItem") {
		$itemId = (int)$_POST["itemId"];
		$imageAdded = (string)($itemId);
	}
		
	if($_FILES["fileToUpload"]["name"] != "")
		uploadCover($_POST['theOperation']);
	
	if(isset($_POST['hasDate']) && $_POST['hasDate'] == "none")
		$dateAdded = null;
	else
		$dateAdded = $_POST['dateAdded'];
	
	
	if($_POST['theOperation'] == "addItem")
		addItem((string)$itemId, $_POST['titleAdded'], $_POST['genreAdded'], $_POST['publisherAdded'], 
			$dateAdded, $imageAdded, $dbConn, $itemTable);
	else if($_POST['theOperation'] == "editItem" && $_FILES["fileToUpload"]["name"] != "")
		updateItem((string)$itemId, $_POST['titleAdded'], $_POST['genreAdded'], 
			$_POST['publisherAdded'], $dateAdded, $imageAdded, $dbConn, $itemTable);
	else if($_POST['theOperation'] == "editItem" && $_FILES["fileToUpload"]["name"] == "")
		updateItem2((string)$itemId, $_POST['titleAdded'], $_POST['genreAdded'], 
			$_POST['publisherAdded'], $dateAdded, $dbConn, $itemTable);
}
##---------------------------------------------------------------

$book = queryDb($sqlCmd, $dbConn);

$sqlCmd = "SELECT * from $genreTable";
$genre = queryDb($sqlCmd, $dbConn);

$sqlCmd = "SELECT * from $publisherTable ORDER BY p_name";
$publisher = queryDb($sqlCmd, $dbConn);

?>
