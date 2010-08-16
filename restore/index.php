<?php
include('../backupClass.php');
require_once('../config.php');
$databasehost = DB_HOST;
$databasename = DB_DATABASE;
$databaseusername = DB_USER;
$databasepassword = DB_PASSWORD;
$con = @mysql_connect($databasehost,$databaseusername,$databasepassword) or die(mysql_error());
@mysql_select_db($databasename) or die(mysql_error());


if(isset($_GET['filename'])){
    $file=$_GET['filename'];
    $splitFilename = explode('|',$file);
    $table = $splitFilename[0];

    replaceTableWithCSVContent($table,$file);

}
else{
    ?>Enter a filename here, or pick one of the ones below to restore from:<form action="index.php" method="GET">
    <input type="text" name="filename" value="" />
    <input type="submit" value="Restore" name="Submit" />
</form><?
    $resulper = mysql_query("SELECT * FROM `backup_history` ORDER BY `filename`");
    echo "<div id='backuptables'><table><tr><td>Table</td><td>Backup Date</td></tr>";
    while($row=mysql_fetch_array($resulper)){
        $splitname = explode('|',$row['filename']);
        echo "<tr><td><a href='index.php?filename=".$row['filename']."'>".$splitname[0]."</a></td><td>".str_replace('.softraderexport','',$splitname[1])."</td></tr>";
    }
    echo "</table></div>";
}
?>