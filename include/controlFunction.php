<?php

function getRetrievalSql($range, $pass, $tables) {
	
	$select1 = "SELECT id, title, $tables[1].genre_name, 
		$tables[2].p_name, release_date, img_url, content FROM $tables[0] ";
	$join1 = "JOIN $tables[1] ON $tables[0].genre = $tables[1].genre_id ";
	$join2 = "JOIN $tables[2] ON $tables[0].publisher = $tables[2].p_id ";
	$order = "ORDER BY release_date DESC ";

	$countsSql = "SELECT count(*) FROM $tables[0] " .$join1 .$join2;
	$retrievalSql = $select1 .$join1 .$join2;
	
	switch($range) {
		case "usual":
			break;
		case "searchGenre&Publisher":
			$condition = "WHERE genre_name = '" .$pass['selGenre'] ."' AND p_name = '"
				.$pass['selPublisher'] ."' ";
			$countsSql .= $condition;
			$retrievalSql .= $condition;
			break;
		case "searchGenre":
			$condition = "WHERE genre_name = '" .$pass['selGenre'] ."' ";
			$countsSql .= $condition;
			$retrievalSql .= $condition;
			break;
		case "searchPublisher":
			$condition = "WHERE p_name = '" .$pass['selPublisher'] ."' ";
			$countsSql .= $condition;
			$retrievalSql .= $condition;
			break;
		case "searchTitle":
			$condition = "WHERE title like '%" .$pass['searchTitle'] ."%' ";
			$countsSql .= $condition;
			$retrievalSql .= $condition;
			break;
	}

	$retrievalSql .= $order;
	return array($countsSql, $retrievalSql);
}

?>
