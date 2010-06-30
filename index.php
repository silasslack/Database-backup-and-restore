<h1>Backups</h1>
<a href="backup.php?table=VENDOR1_ORDERS">Main Order Table</a><br />
<a href="backup.php?table=DOCUMENTS">Deliveries Table</a><br />
<a href="backup.php?table=all">All</a><br />
<?
require_once('config.php');
$databasehost = DB_HOST;
$databasename = DB_DATABASE;
$databaseusername = DB_USER;
$databasepassword = DB_PASSWORD;
$con = @mysql_connect($databasehost,$databaseusername,$databasepassword) or die(mysql_error());
@mysql_select_db($databasename) or die(mysql_error());
$sql = "SHOW TABLES FROM $databasename";
$result = mysql_query($sql);

if (!$result) {
    echo "DB Error, could not list tables\n";
    echo 'MySQL Error: ' . mysql_error();
    exit;
}

while ($row = mysql_fetch_row($result)) {
    echo "Backup: <a href='backup.php?table={$row[0]}'>{$row[0]}</a><br />";
}



?>