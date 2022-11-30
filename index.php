<?php

require_once("include/header.php");
require_once("include/configure.php");
require_once("include/dbFunction.php");
require_once("include/controlFunction.php");
require_once("include/uploadImage.php");

require_once("include/control.php");
require_once("include/view.php");

?>

<body>

<div class="home">
	<a href="index.php">首頁</a>
</div><br/>

<!-- button for adding new items to DB -->
<div class="block">
	<input class="add" type="button" value="新增">
</div><br/>

<form class="selectGenrePublisher" method="GET" action="">
	<!-- drop down menu for viewing items by genre -->
	類型: 
	<select name="selGenre">
		<option value="全部"
		
		<?php
		
		if( isset($_GET['selGenre']) && $_GET['selGenre'] == "全部")
			echo " selected";
		
		echo ">全部</option>";
		
		foreach($genre as $theGenre) {
			$genreName = $theGenre['genre_name'];
			echo "<option value=\"$genreName\" ";
			
			if( isset($_GET['selGenre']) && $_GET['selGenre'] == $genreName)
				echo "selected";
			
			echo ">$genreName</option>";
		}
		?>
		
	</select>
	
	<!-- drop down menu for viewing items by publisher -->
	出版社:
	<select name="selPublisher">
		<option value="全部"
		
		<?php
		
		if(isset($_GET['selPublisher']) && $_GET['selPublisher'] == "全部")
			echo " selected";
		
		echo ">全部</option>";
		
		foreach($publisher as $thePubli) {
			$publisherName = $thePubli['p_name'];
			echo "<option value=\"$publisherName\" ";
			
			if(isset($_GET['selPublisher']) && $_GET['selPublisher'] == $publisherName)
				echo "selected ";
			
			echo ">$publisherName</option>";
		}
			
		?>
		
	</select>
	<input type="submit" value="查看">
	
	<!-- search block -->
	<div class="block2-2">
	<input type="text" name="searchTitle"  size="10">
	<button class="searchActive" onclick="submit();" >搜尋</button>
	</div>
</form>

<?php

if(count($book) != 0)
	showItem($book);
else
	echo '<div style="text-align: center;">查無項目</div>';

?>

<div class="page">
<form name="selPage" method="GET" action="">
	<?php
	
	if($page > 1)
		echo "<button class=\"lastPage\" type=\"submit\" name=\"lastPage\" ></button>";

	if($totalPage > 1) {
		echo '<select name="page" onchange="submit();">';
		
		for ($p=1; $p<=$totalPage; $p++) { 
			echo '  <option value="' . $p . '"';
			
			if ($p == $page) 
				echo ' selected';
			
			echo ">P.$p</option>\n";
		}
		
		echo '</select>';
	}
	
	if($page < $totalPage) 
		echo "<button class=\"nextPage\" type=\"submit\" name=\"nextPage\" ></button>";
	
	?>
</form>
</div>

<div id="operationDialog" style="text-align:center;">
<form method="POST" action="" id="addItem" enctype="multipart/form-data">
	名稱:
	<input id="addTitle" name="titleAdded" type="text" size="10" autofocus>
	<p id="titleInfo" style="color:red;"></p>
	出版社:
	<select id="addPublisher" name="publisherAdded" >
		<?php
		
		foreach($publisher as $thePubli) {
			$publisherID = $thePubli['p_id'];
			$publisherName = $thePubli['p_name'];
			echo "<option value=\"$publisherID\">$publisherName</option>";
		}
		
		?>
	</select><br/><br/>
	類型:
	<select id="addGenre" name="genreAdded" >
		<?php
		
		foreach($genre as $theGenre) {
			$genreID = $theGenre['genre_id'];
			$genreName = $theGenre['genre_name'];
			echo "<option value=\"$genreID\">$genreName</option>";
		}
		
		?>
	</select><br/><br/>
	發售日期:
	<input type="date" id="addDate" name="dateAdded" min= "1970-01-01" max="2030-12-31">
	<input type="checkbox" name="hasDate" value="none">未定<br/><br/>
	<input type="hidden" name="MAX_FILE_SIZE" value="30000" />
	封面圖片:
	<input type="file" name="fileToUpload" id="fileToUpload"><br/><br/>
	<input class="submitAdd" type="submit" value="確認" form="addItem">
	<input class="cancelAdd" type="button" value="取消">
	<!-- theOperation: 按新增按鈕時value設為addItem, 按編輯按鈕時value設為editItem-->
	<input id="theOperation" name="theOperation" type="text" value="" style="display: none;">
	<input id="itemId" name="itemId" type="text" value="" style="display: none;">
</form></div>

<script>
$("#operationDialog").dialog({
	width: 430,
	position: { my: "center", at: "top", of: window },
	autoOpen: false
});

$("div.contentDialog").each(function() {
	$(this).dialog({
		autoOpen: false
	});
});

$(document).ready(function() {
	$("input.add").click(function(event){
		$("#operationDialog").dialog({
			title: "新增作品"
		});
		
		//initialize form
		document.getElementById("theOperation").value = "addItem";
		document.getElementById("addTitle").value = "";
		document.getElementById("addPublisher").value = document.getElementById("addPublisher").options[0].value;
		document.getElementById("addGenre").value = document.getElementById("addGenre").options[0].value;
		document.getElementById("addDate").value = "";
		
		$("#operationDialog").dialog("open");
	});
	
	$("button.editButton").click(function(event) {
		$("#operationDialog").dialog({
			title: "編輯作品"
		});
		
		document.getElementById("theOperation").value = "editItem";
		
		var itemNo = event.target.id.slice(1);
		document.getElementById("itemId").value = document.getElementById("id"+itemNo).innerHTML;
		document.getElementById("addTitle").value = document.getElementById("title"+itemNo).innerHTML;
		
		var theValue;
		var select = document.getElementById("addPublisher");
		var item = document.getElementById("publi"+itemNo).innerHTML.slice(4);
		
		for(var i=0; i<select.length; i++) {
			
			if(item == select.options[i].text) {
				theValue = select.options[i].value;
				break;
			}		
		}
		
		select.value = theValue;
		
		select = document.getElementById("addGenre");
		item = document.getElementById("type"+itemNo).innerHTML.slice(4);
		
		for(var i=0; i<select.length; i++) {
			
			if(item == select.options[i].text) {
				theValue = select.options[i].value;
				break;
			}
		}
		
		select.value = theValue;
		
		document.getElementById("addDate").value = document.getElementById("date"+itemNo).innerHTML.slice(4);
		
		$("#operationDialog").dialog("open");
	});
	/*$("input.submitAdd").click(function(event){
		var theTitle = document.getElementById("addTitle").value;
		if(theTitle === "")
			document.getElementById("titleInfo").innerHTML = "請輸入名稱";
	});*/
	
	$("input.cancelAdd").click(function(event){
		$("#operationDialog").dialog("close");
		document.getElementById("addTitle").value = "";
		document.getElementById("titleInfo").innerHTML = "";
	});
	
    $("button.itemButton").click(function(event) {
        var contentPanelId = event.target.id;
		var cid = "#c" + contentPanelId.toString();
		
		$(cid).dialog({
			position: { my: "center", at: "center", of: event }
		});
		
		$(cid).dialog("open");		
    });
});
</script>

</body>
</html>