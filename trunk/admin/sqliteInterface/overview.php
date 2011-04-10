<?php
	include("navigation.php");
	require_once("ArrayTable.php");

	require_once("../../library/Cryptastic.php");
	$masterKeyFile = "../../safe/master.key";

	if( isset( $_POST["AT"] ) )
	{
		addTable();
	}

	if( isset( $_POST["drop"] ) )
	{
		removeTable();
	}

	if( isset( $_POST["ATI_DELETE"] ) )
	{
		delete();
	}

	if( isset($_POST["ATI_KEYS"] ) && isset( $_POST["ATI_INSERT"] ) ){
		save();
	}

	if( !isset( $_SESSION["basename"] ) && !isset( $_SESSION["tablename"] ) ){
		echo "Nothing Selected!";
		exit();	
	}

	if( isset($_SESSION["tablename"]) ){
		showTable($_SESSION["basename"], $_SESSION["tablename"]);
		exit();
	}	

	if( isset( $_SESSION["query"] ) ){
		if( !isset( $_SESSION["basename"] ) ){
			echo "no database selected";
			exit();		
		}
		showQuery( $_SESSION["query"] );
		unset( $_SESSION["query"] );
		exit();
	}

	
	if( isset($_SESSION["basename"]) ){
		
		if( file_exists( $_SESSION["basename"] ) ){
			showBase( $_SESSION["basename"] );
		}
		else {
			createBase( $_SESSION["basename"] );
			showBase( $_SESSION["basename"] );
		}
		
		exit();
	}


	function createBase( $basename ){
		$base=new SQLiteDatabase($basename, 0666, $err);

		if ($err){ 
			echo "SQLite NOT supported.\n";
			exit($err);
		}
	
		echo "successfully created new database: ".$basename;
	}
	
	function save(){
		global $masterKeyFile;
		
		$crypter = new Cryptastic();
		$encryptionKey = file_get_contents($masterKeyFile);

		$base = new SQLiteDatabase($_SESSION["basename"]);
		$query = "INSERT INTO ".$_SESSION["tablename"]."(".$_POST["ATI_KEYS"].") VALUES(";

		$keys = explode(",", $_POST["ATI_KEYS"]);
		$i = count($keys);

		foreach( $keys as $key){
			$i--;

			if( isset( $_POST["ATI_".$key."_ENCODE"] ) ){
				$query .= $_POST["ATI_".$key] == ""	? "null" : "'".SQLite3::escapeString( $crypter->encrypt( $_POST["ATI_".$key], $encryptionKey ) )."'";
				echo $crypter->encrypt($_POST["ATI_".$key]);
			}
			else
				$query .= $_POST["ATI_".$key] == ""	? "null" : "'".SQLite3::escapeString($_POST["ATI_".$key])."'";
			
			$query .= $i == 0 ? ")" : ",";
		}
		$base->query( $query, SQLITE_ASSOC );
	}
	
	function delete(){
		$count = $_POST["ATI_DELETE_ID_COUNT"];
		
		$targets = array();
		
		for( $i=0; $i<$count; $i++ ){
			if( isset( $_POST["ATI_DELETE_ID_".$i] ) ){
				 $targets[] = $_POST["ATI_DELETE_ID_".$i];
			}
		}
		
		if( count($targets) == 0 )
			return;
		
		$query = "DELETE FROM ".$_SESSION["tablename"]." WHERE ROWID IN (".implode($targets,",").")";
		$base = new SQLiteDatabase($_SESSION["basename"]);
		$base->query($query);
	}
	
	function addTable(){
		$names = explode( ",", $_POST["AT_names"] );
		$types = explode( ",", $_POST["AT_types"] );
		$query = "CREATE TABLE ".$_POST["AT_tablename"]."(";
		
		if( count($names) != count($types) ){
			echo "count of names and types mimatch!";
			return;
		}
		
		foreach($names as $i => $name){
			$query .= "'".$name."' ".$types[$i];
			$query .= $i == count($names)-1 ? ")" : ",";
		}
		
		$base = new SQLiteDatabase($_SESSION["basename"]);
		$base->query($query);
		$_SESSION["tablename"] = $_POST["AT_tablename"];

		echo "<meta http-equiv='refresh' content='0'>";
		flush();
	}
	
	function removeTable(){
		$base = new SQLiteDatabase($_SESSION["basename"]);
		$base->query("DROP TABLE ".$_SESSION["tablename"]);
		unset( $_SESSION["tablename"] );
		
		echo "<meta http-equiv='refresh' content='0'>";
		flush();
	}
	
	function showBase( $basename ){
		echo "<h3> Contents of Database: ".$basename."</h3>";
		
		$at = new ArrayTable("SELECT * FROM sqlite_master WHERE type='table'");
		echo $at->getTable();
		echo "<br><br><hr><b>Add Table:</b><br><br><form method='post' action='".$_SERVER['PHP_SELF']."'><table>";
		echo "<tr><td>Table-Name:</td><td> <input type='text' name='AT_tablename'></td></tr>";
		echo "<tr><td>Column-Names:</td><td> <input type='text' name='AT_names'></td></tr>";
		echo "<tr><td>Column-Types: </td><td><input type='text' name='AT_types'></td></tr>";
		echo "</table>";
		echo "<br>seperate names and types with comma!<br><br><input type=submit value='add table' name='AT'></form>";				
	}

	function showTable( $basename, $tablename ){
		echo "<h3> Contents of Table: ".$tablename."</h3>";
		
		$at = new ArrayTable("SELECT * FROM ".$tablename);
		$at->enableInputFields(true);
		$at->enableCheckboxes(true);
		echo $at->getTable();				
	}
	
	function showQuery( $query ) {
		$at = new ArrayTable($query);

		echo "<h3>Executed Query:</h3>";			
		echo "<b>Query:</b> \"".$query."\"<br><br>";
		echo "<b>Result:</b><br>".$at->getTable();
		
		updateNavigation();			
	}
	
?>
