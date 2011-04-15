<?php session_start() ?>

<html>
<link rel="stylesheet" type="text/css" href="../../style/style.css">
<form method="post" action="overview.php">

	<table>
	<tr>
	<td width="330"> Database: <input type="text" id="baseTextField" name="basename"> </td>
	<td> Table: <select id="possibleTables" name="tablename"> <option>--All--</option></select> </td>
	<td width="200"> <input name="select" type="submit" value="select"> </td>
	
	<td> <input type="text" name="query"> </td>
	<td> <input type="submit" value="send query"> </td>
	</tr>
	</table>
</form>



<hr>


</html>


<script type="text/javascript">

	function updateSelectedDb(db){
		document.getElementById("baseTextField").value = db;	
	}

	function updateDropdown(args){
		if(args == "") return;
		args = args.split(",");	

		for( var i in args){
			var opt = document.createElement("option");
		 	document.getElementById("possibleTables").options.add(opt);
			opt.text = args[i];
			opt.name = args[i];
		}
	}
	
	function selectDropdown(optionName){
		var options = document.getElementById("possibleTables").options;

		for(var i in options){
			if(options[i].name == optionName)
				options.selectedIndex = i;
		}
	}

</script>

<?php
	function updateNavigation(){
		$base = new SQLiteDatabase($_SESSION["basename"]);
		$res = $base->arrayQuery("SELECT * FROM sqlite_master WHERE type='table'", SQLITE_ASSOC);
		
		$names = array();
		foreach( $res as $r )		
			$names[] = $r["name"];

		echo "<script type='text/javascript'> updateSelectedDb('".$_SESSION["basename"]."'); </script>";	
		echo "<script type='text/javascript'> updateDropdown('".implode($names,",")."'); </script>";	
	}

	if( isset( $_POST["basename"] ) && $_POST["basename"] != "" ){
		if( $_POST["basename"] != $_SESSION["basename"] ) $_POST["tablename"] = "--All--";
		$_SESSION["basename"] = $_POST["basename"];
	}

	if( isset( $_SESSION["basename"] ) && !( isset( $_POST["query"] ) && $_POST["query"] != "" ) ){
		updateNavigation();
	}

	if( isset( $_POST["tablename"] ) ){
		if( $_POST["tablename"] != "--All--" ){
			$_SESSION["tablename"] = $_POST["tablename"];
		}
		else 
			unset( $_SESSION["tablename"] );
	}

	if( isset( $_SESSION["tablename"] ) ){
			echo "<script type='text/javascript'> selectDropdown('".$_SESSION["tablename"]."'); </script>";	
	}

	if( isset( $_POST["query"] ) && $_POST["query"] != "" ){
		$_SESSION["query"] = $_POST["query"];
		unset( $_SESSION["tablename"] );
	}
?>


