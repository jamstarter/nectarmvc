<?php
$commands[] = array('command'=>'updatedb [module/all]','description'=>'Update the database from module YAML files');

use Symfony\Component\Yaml\Yaml;

function updatedb($module){
	if(trim($module) == ''){
		die("Please specify a module name or 'all'! \n");
	}
	$modules = array();
	if(trim($module) <> 'all'){	
		$modules[] = $module;
	} else {
		$folders = scandir(APPLICATION_PATH.'/app/modules');
		foreach($folders AS $folder){
			if($folder <> '.' && $folder <> '..'){
				$modules[] = $folder;
			}
		}
	}
	echo "Updating DB for $module...\n";
	//get config
	$config = new \system\Config;
	$application = $config->getConfig();

	foreach($modules AS $module){
		$modulePath = APPLICATION_PATH.'/app/modules/'.$module;
		$dbMapPath = trim($modulePath."/db/db.yml");

		try{
		$dbMap = Yaml::parse(file_get_contents($dbMapPath));

		$dbAdapter = new \system\Model;
		$db = $dbAdapter->_rawDb;

		$sql = "select * from information_schema.columns where table_schema = '".$application->resources->db->database."' ";

		$tables = $db->query($sql)->fetchAll();
		foreach($tables AS $table){
			$existingTableName = $table['TABLE_NAME'];

			$exists = '0';
			foreach($dbMap['tables'] AS $tableName => $tableData){
				if($tableName == $existingTableName){
					$exists = '1';
				}
			}

		/*	
		//disabled for now due to conflicts with modules dropping tables for other modules
		if($exists == '0'){
				echo "$existingTableName removed from map, dropping table....\n";
				$sure = readline("Drop $existingTableName from database? (y/n)\n");
				if(strtolower($sure) == 'y'){
					$db->query("DROP TABLE $existingTableName");
					echo "$existingTableName dropped from database....\n";
					break(1);
				} else {
					echo "$existingTableName not dropped....\n";
					break(1);
				}
			}
		*/	
		}
		if(count($dbMap['tables']) == 0){
			die();
		}
		foreach($dbMap['tables'] AS $tableName => $tableData){
			echo $module." : ".$tableName."\n";
			$mappedColumns = $tableData['columns'];


			

			$sql = "select * from information_schema.columns where table_schema = '".$application->resources->db->database."' and table_name='".$tableName."' ";

			$columns = $db->query($sql)->fetchAll();
			
			
			if(!empty($columns)){
				//table exists, update existing columns
				foreach($columns AS $column){
					$type = trim(@$tableData['columns'][$column['COLUMN_NAME']]['type']);
					$default = trim(@$tableData['columns'][$column['COLUMN_NAME']]['default']);
					$extra = trim(@$tableData['columns'][$column['COLUMN_NAME']]['extra']);

					//check if column still exists in map
					if(!isset($tableData['columns'][$column['COLUMN_NAME']])){
						echo $column['COLUMN_NAME']." removed from map, dropping from db...\n";
						//column does not exist in map, drop column from db
						$db->query("ALTER TABLE `".$tableName."` DROP COLUMN `".$column['COLUMN_NAME']."`");
						continue;
					}

					//check to see if column needs to be updated, if so, update
					if(strtolower($type) <> strtolower($column['COLUMN_TYPE']) || strtolower($default) <> strtolower($column['COLUMN_DEFAULT']) || strtolower($extra) <> strtolower($column['EXTRA'])){
						echo $column['COLUMN_NAME']." changed in map, updating column definition...\n";
						//existing column differs from map, update
						if(trim($default) <> ''){
							$db->query("ALTER TABLE `".$tableName."` MODIFY COLUMN `".$column['COLUMN_NAME']."` $type DEFAULT $default $extra");
						} else {
							$db->query("ALTER TABLE `".$tableName."` MODIFY COLUMN `".$column['COLUMN_NAME']."` $type $extra");
						}
						continue;
					}


				}

				$sql = "select * from information_schema.columns where table_schema = '".$application->resources->db->database."' and table_name='".$tableName."' ";

				$columns = $db->query($sql)->fetchAll();

				//add any columns not already added/updated
				foreach($mappedColumns AS $columnName => $mappedData){
					$exists = '0';
					foreach($columns AS $column){
						if($column['COLUMN_NAME'] == $columnName){
							$exists = '1';
						}
					}

					//column does not exist, add it
					if($exists <> '1'){
						echo $columnName." not yet in db, adding new column...\n";

						$type = trim(@$mappedData['type']);
						$default = trim(@$mappedData['deafult']);
						$extra = trim(@$mappedData['extra']);

						//column does not exist, create it
						if(trim($default) <> ''){
							$db->query("ALTER TABLE `".$tableName."` ADD COLUMN `".$columnName."` $type DEFAULT $default $extra");
						} else {
							$db->query("ALTER TABLE `".$tableName."` ADD COLUMN `".$columnName."` $type $extra");
						}
						continue;
					}
				}


			} else {
				//table does not exist, create
				echo "Creating new table $tableName....\n";
				$sql = "CREATE TABLE IF NOT EXISTS `$tableName` (";
					foreach($mappedColumns AS $name => $newColumn){
						$type = trim(@$newColumn['type']);
						$default = trim(@$newColumn['deafult']);
						$extra = trim(@$newColumn['extra']);

						if(trim($default) == ''){
							$sql .= "`".$name."` $type $extra,";
						} else {
							$sql .= "`".$name."` $type DEFAULT $default $extra,";
						}
					}
				$sql .= "PRIMARY KEY (`".$tableData['primary_key']."`) )";
				$db->query($sql);
			}

		}

		} catch (Exception $e){
			echo $e->getMessage()."\n";
		}
	}
}