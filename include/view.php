<?php


function showItem($book) {
	echo '<div class="zone2"><br />';
	echo '<div class="page_zone">';

	$i = 1;
	foreach( $book as $item) {
		$title = $item['title'];
		$type = $item['genre_name'];
		$publisher = $item['p_name'];
		$releaseDate = $item['release_date'];
		
		if($releaseDate == "0000-00-00")
			$releaseDate = "未定";

		$imgUrl = $item['img_url'];
		$content = $item['content'];
		
		if($content == "")
			$content = "暫無簡介";
		
		$id = $item['id'];
		

	echo "
	<p id=\"id$i\" style=\"display: none;\">$id</p>
	<table class=\"item\">
		<tr>
			<td>
				<button class=\"itemButton\" id=\"$i\" style=\"background-image: url('$imgUrl');border: 0; height: 174; width: 174; background-size: 100%\"></button>
			</td>
		</tr>
		<tr>
			<td><div style=\"text-align: right;\"><button class=\"editButton\" id=\"e$i\">編輯</button><div/></td>
		</tr>
		<tr>
			<td id=\"title$i\">$title</td>
		</tr>
		<tr>
			<td id=\"type$i\">類型: $type</td>
		</tr>
		<tr>
			<td id=\"publi$i\">出版: $publisher</td>
		</tr>
		<tr>
			<td id=\"date$i\">日期: $releaseDate</td>
		</tr>
	</table>
	<table class=\"blankSpace\">
		<tr>
			<td></td><td></td><td></td>
		</tr>
		<tr>
			<td></td><td></td><td></td>
		</tr>
	</table>
	<div class=\"contentDialog\" id=\"c$i\" title=\"$title\" style=\"display: none;\">
	<p>$content</p></div>";

	if($i % $GLOBALS["itemPerLine"] == 0)
		echo "<br/>";
	
	$i++;
	}
	echo
	"</div><br />
	</div><br />";

}

?>