<?php
require_once('../../config.php');
$databasehost = DB_HOST;
$databasename = DB_DATABASE;
$databaseusername = DB_USER;
$databasepassword = DB_PASSWORD;
$con = @mysql_connect($databasehost,$databaseusername,$databasepassword) or die(mysql_error());
@mysql_select_db($databasename) or die(mysql_error());
$date = date("Y-m-d-H-i-s");
$table = $_GET['tabletorestore'];

$sql = "RENAME TABLE `".$databasename."`.`".$table."` TO `".$databasename."`.`".$table."_oldversion_".$date."`, `".$databasename."`.`".$table."_frombackup` TO `".$databasename."`.`".$table."`";
if(!mysql_query($sql)){
    die("could not move tables!<br />".mysql_error());
}
else{
    echo "Restore Complete!<br /><a href='index.php'>Go Back</a>";
}
?>