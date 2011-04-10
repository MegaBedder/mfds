<?php

	Class ArrayTable{
		protected $query = "";
		protected $inputFields = false;
		protected $checkboxes = false;
		
		function ArrayTable( $query ){
			$this->query = $query;		
		}		

		function enableInputFields( $bool ){
			$this->inputFields = $bool;		
		}

		function enableCheckboxes( $bool ){
			$this->checkboxes = $bool;
		}

		function getTable(){
			$base = new SQLiteDatabase($_SESSION["basename"]);
			
			if( isset( $_SESSION["tablename"] ) ){
				$tmp = $base->arrayQuery("PRAGMA table_info(".$_SESSION["tablename"].")");
				$keys = array();
				$types = array();
				
				foreach($tmp as $row){
					$keys[] = $row["name"];
					$types[] = $row["type"];
				}
				
				$this->query = substr($this->query, 0, 7)." ROWID,".substr($this->query, 7);
				$res = $base->arrayQuery($this->query, SQLITE_ASSOC);
			}
			else {
				$res = $base->arrayQuery($this->query, SQLITE_ASSOC);
				$keys = array_keys($res[0]);
			}

			$table = "";

			if($this->inputFields || $this->checkboxes)
				$table .= "<form method='post' action='".$_SERVER['PHP_SELF']."'>";

			$table .= "<table border='none'><tr>";
			
			foreach( $keys as $key ){
				$table .= "<th>".$key."</th>";			
			}
			
			$table .= "</tr>";

			$i = 0;
			foreach( $res as $row ){
				$table .= "<tr>";
				
				foreach( $keys as $key ){
					$table .= "<td>".( $row[$key] == "" ? "&nbsp;" : $row[$key] )."</td>";
				}
			
				if( $this->checkboxes ){
					$table .= "<td><input type='checkbox' value='".$row["ROWID"]."' name='ATI_DELETE_ID_".$i."'></td>";
				}
				$table .= "</tr>";
				$i++;
			}

			if( $this->inputFields ){
				$table .= "<tr>";
				foreach( $keys as $key ){
					$table .= "<td><input type='text' name='ATI_".$key."'></td>";
				}	
				$table .= "<input type='hidden' name='ATI_KEYS' value='".implode($keys,",")."'>";
			}

			if( $this->checkboxes ){
				$table .= "<input type='hidden' name='ATI_DELETE_ID_COUNT' value='".count($res)."'>";
			}

			$table .= "</table>";
			
			if($this->inputFields)
				$table .= "<br><input type=submit value='insert' name='ATI_INSERT'>";
			if($this->checkboxes)
				$table .= "<input type=submit value='delete' name='ATI_DELETE'>";
				
			if($this->inputFields || $this->checkboxes)
				$table .= "</form>";

			return $table;		
		}
	}

?>
