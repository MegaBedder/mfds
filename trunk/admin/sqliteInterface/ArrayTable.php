<script type="text/javascript">
	
	function checkAll(form) {
		 var myForm = document.forms[form];
		 for( var i=0; i < myForm.length; i++ ) { 
		      if(myForm.elements[i].checked) {
		           myForm.elements[i].checked = "";
		      } 
		      else {
		          myForm.elements[i].checked = "checked";
		      }
		 }
	}
	
</script>
<?php
	require_once("../../library/Cryptastic.php");
	$masterKeyFile = "../../safe/master.key";

	Class ArrayTable{
		protected $query = "";
		protected $inputFields = false;
		protected $checkboxes = false;
		
		
		function escapeJavaScriptText($string){
			return strtr($string, array('\\'=>'\\\\',"'"=>"\\'",'"'=>'\\"',"\r"=>'\\r',"\n"=>'\\n','</'=>'<\/'));
		}

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
			global $masterKeyFile;

			$crypter = new Cryptastic();
			$encryptionKey = file_get_contents($masterKeyFile);

			$base = new SQLiteDatabase($_SESSION["basename"]);
			
			if( isset( $_SESSION["tablename"] ) ){
				$tmp = $base->arrayQuery("PRAGMA table_info(".$_SESSION["tablename"].")");
				$keys = array();
				$types = array();
				
				foreach($tmp as $row){
					$keys[] = $row["name"];
					$types[ $row["name"] ] = $row["type"];
				}
				
				$this->query = substr($this->query, 0, 7)." ROWID,".substr($this->query, 7);
				$res = $base->arrayQuery($this->query, SQLITE_ASSOC);
			}
			else {
				$res = $base->arrayQuery($this->query, SQLITE_ASSOC);
				if(count($res) == 0){
					echo "no content present<br>";
					return;
				}
				$keys = array_keys($res[0]);
			}

			$table = "";

			if($this->inputFields || $this->checkboxes || isset( $_SESSION["tablename"] ) )
				$table .= "<form name='arrayTable' method='post' action='".$_SERVER['PHP_SELF']."'>";

			$table .= "<table border='none'><tr>";
			
			foreach( $keys as $key ){
				if( preg_match("/binary/i", $types[$key]) ) 
					$table .= "<th>".$key."<input type='hidden' name='ATI_".$key."_ENCODE'>&nbsp;&nbsp;<img src='lock.gif'></th>";
				else
					$table .= "<th>".$key."</th>";			
			}
			
			if($this->checkboxes) $table .= "<td><input type='button' value='all' onclick='checkAll(\"arrayTable\");'></td>";
		
			$table .= "</tr>";

			$i = 0;
			foreach( $res as $row ){
				$table .= "<tr>";
				
				foreach( $keys as $key ){
					if( preg_match("/binary/i", $types[$key]) ){
						if($row[$key] != ""){
							$table .= "<td>ENCRYPTED";
							$table .= "&nbsp;&nbsp;&nbsp;<a href='' onclick='alert(\"Decryption: ".$crypter->decrypt($row[$key], $encryptionKey)."\");'>";
							$table .= "<img title='decode' src='unlock.gif'></a>";
						}
						else
							$table .= "<td>&nbsp;</td>";
					}
					else $table .= "<td>".( $row[$key] == "" ? "&nbsp;" : $row[$key] );
				}
				
				if( $this->checkboxes ){
					$table .= "<td><center><input type='checkbox' value='".$row["ROWID"]."' name='ATI_DELETE_ID_".$i."'></center></td>";
				}
				$table .= "</tr>";
				$i++;
			}

			if( $this->inputFields ){
				$table .= "<tr>";
				foreach( $keys as $key ){
					$table .= "<td><input type='text' name='ATI_".$key."'>";
					$table .= "</td>";	
				}
				
				if($this->checkboxes) $table .= "<td>&nbsp;</td>";	
				$table .= "<input type='hidden' name='ATI_KEYS' value='".implode($keys,",")."'>";
			}

			if( $this->checkboxes ){
				$table .= "<input type='hidden' name='ATI_DELETE_ID_COUNT' value='".count($res)."'>";
			}

			$table .= "</table>";
			
			if($this->inputFields)
				$table .= "<br><input type=submit value='insert' name='ATI_INSERT'>";
			if($this->checkboxes && count($res) != 0)
				$table .= "<input type=submit value='delete' name='ATI_DELETE'>";
			if( isset( $_SESSION["tablename"] ) )
				$table .= "<input type=submit value='drop table' name='drop'>";
				
			if($this->inputFields || $this->checkboxes || isset( $_SESSION["tablename"] ))
				$table .= "</form>";

			return $table;		
		}
	}

?>
