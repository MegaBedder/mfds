<?php
	include("navigation.php");
	require_once("ArrayTable.php");

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
		$base = new SQLiteDatabase($_SESSION["basename"]);
		$query = "INSERT INTO ".$_SESSION["tablename"]."(".$_POST["ATI_KEYS"].") VALUES(";

		$keys = explode(",", $_POST["ATI_KEYS"]);
		$i = count($keys);

		foreach( $keys as $key){
			$i--;
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
	
	function showBase( $basename ){
		echo "<h3> Contents of Database: ".$basename."</h3>";
		
		$at = new ArrayTable("SELECT * FROM sqlite_master WHERE type='table'");
		echo $at->getTable();				
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
	}
	
?>
