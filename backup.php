<?php
require_once('../config.php');
include('backupClass.php');
$databasehost = DB_HOST;
$databasename = DB_DATABASE;
$databaseusername = DB_USER;
$databasepassword = DB_PASSWORD;
$con = @mysql_connect($databasehost,$databaseusername,$databasepassword) or die(mysql_error());
@mysql_select_db($databasename) or die(mysql_error());
$tName = $_GET['table'];

if ($tName=='all'){
    $result = mysql_query("SHOW TABLES");
    if (!$result) {
    echo "DB Error, could not list tables\n";
    echo 'MySQL Error: ' . mysql_error();
    exit;
}

while ($row = mysql_fetch_row($result)) {

    if(!backUpTable("{$row[0]}")){
    echo "error! Could no back up table: {$row[0]}. this could be because it has no content";
    }
}
}
else{
    if(!backUpTable($tName)){
    echo "error! Could no back up table: ".$tName.". this could be because it has no content";
}
}





?>
