<?php
function backUpTable($tName){
    $date = date("Y-m-d");
    $res = mysql_query("SELECT * FROM `$tName`");

for ($num=0;$num<mysql_num_fields($res);$num++){
    $metadata = mysql_fetch_field($res,$num);
    $fieldname[$num] = $metadata->name;
    $fieldlength[$num] = $metadata->max_length;
    $fieldtype[$num] = $metadata->type;
}

$filename = "backup_files/".$tName."|".$date.".softraderexport";
$fileHandle = fopen($filename,'w');

fwrite($fileHandle,"/////Line 1 is the table name, line 2 are the field names, line 3 are the field lengths and line 4 are the field types\n");
fwrite($fileHandle,$tName."\n");

$line='';
for ($num=0;isset($fieldname[$num]);$num++){
    if($num!=count($fieldname)-1){
        $line = $line.$fieldname[$num]."|";
    }
    else{
        $line = $line.$fieldname[$num];
    }
}
fwrite($fileHandle,$line."\n");

$line='';
for ($num=0;isset($fieldlength[$num]);$num++){
    if($num!=count($fieldlength)-1){
        $line = $line.$fieldlength[$num]."|";
    }
    else{
        $line = $line.$fieldlength[$num];
    }
}
fwrite($fileHandle,$line."\n");

$line='';
for ($num=0;isset($fieldtype[$num]);$num++){
    if($num!=count($fieldtype)-1){
        $line = $line.$fieldtype[$num]."|";
    }
    else{
        $line = $line.$fieldtype[$num];
    }
}
fwrite($fileHandle,$line."\n");



$lineCount=0;
$errorCount=0;
while($row = mysql_fetch_array($res)){
    $line='';
    for($num=0;$num<count($fieldname);$num++){
        if($num!=count($fieldname)-1){
        $line = $line.mysql_real_escape_string(str_replace('|','',$row[$fieldname[$num]]))."|";
        }
        else{
        $line = $line.mysql_real_escape_string(str_replace('|','',$row[$fieldname[$num]]));
        }
    }

if(!fwrite($fileHandle,$line."\n")){
    echo "Error writing line ".$lineCount."<br />";
    $errorCount++;
}

$lineCount++;

}
echo "<br /><br />Backup Complete on table: ".$tName.".<br />Line count: ".$lineCount."<br />Error Count: ".$errorCount."<br /><br />";

$fCreated = str_replace('backup_files/','',$filename);


$sql = "INSERT INTO `backup_history` (`filename`) VALUES ('$fCreated')";
if(!mysql_query($sql)){
    echo "could not update history!";

}

return $lineCount;
return $errorCount;


}



function replaceTableWithCSVContent($table,$file){

$file='../backup_files/'.$file;

    if(!file_exists($file)){
        die("Specified file does not exist!");
    }
    $fileHandle = fopen($file,'r');

    $num=0;
    $linesInserted=0;
    while(!feof($fileHandle)){
        $num++;

        $line = fgets($fileHandle);
        $lineArray = explode('|',$line);


        $oldTypes=array("string", "blob", "real", "date", "int", "time");
        $newTypes=array("VARCHAR", "TEXT", "FLOAT(7,2)", "DATE", "INT", "TIME");



        if($num==1){

        }
        elseif($num==2){

            $tname=$lineArray[0];
        }
        elseif($num==3){

            $fieldsArray=$lineArray;
        }
        elseif($num==4){

            $lengthsArray=$lineArray;
        }
        elseif($num==5){

            $typesArray=$lineArray;
            $typesArray = str_replace($oldTypes,$newTypes,$typesArray);
            for($no=0;$no<count($fieldsArray);$no++){

                    $lengthsArray[$no]='('.$lengthsArray[$no].')';
                    $typesArray[$no]=trim($typesArray[$no]);
                if($typesArray[$no]=='DATE'||$typesArray[$no]=='FLOAT(7,2)'||$typesArray[$no]=='TIME'){
                    $lengthsArray[$no]='';
                }


                if($no==0){

                    $createStatement='CREATE TABLE '.trim($tname).'_frombackup (`'.$fieldsArray[$no].'` '.$typesArray[$no].$lengthsArray[$no].', ';
                }
                elseif($no==count($fieldsArray)-1){
                    $createStatement=$createStatement.'`'.trim($fieldsArray[$no]).'` '.$typesArray[$no].$lengthsArray[$no].')';
                }
                else{
                    $createStatement=$createStatement.'`'.trim($fieldsArray[$no]).'` '.$typesArray[$no].$lengthsArray[$no].', ';
                }
            }
            if(!mysql_query($createStatement)){
                echo "<br />".$createStatement;
                die('<br />Could not create table!'.mysql_error());

            }
            else{
                $created=true;
            }

        }
        else{
            for($no=0;$no<count($lineArray);$no++){
                if($no==0){
                    $insertStatement = "INSERT INTO ".trim($tname)."_frombackup VALUES('".$lineArray[$no]."',";
                }
                elseif($no==count($lineArray)-1){
                    $insertStatement=$insertStatement."'".$lineArray[$no]."')";
                }
                else{
                    $insertStatement=$insertStatement."'".$lineArray[$no]."', ";
                }
            }
            if(mysql_query($insertStatement)){
                $linesInserted++;
            }
        }
    }
    if($created & $linesInserted>0){
        echo $linesInserted." lines have been created. If this seems correct you can click <a href='commitChanges.php?tabletorestore=".$tname."'>HERE</a> to commit the changes to the database.";
    }
}

?>
