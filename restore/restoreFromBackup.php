<?php

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
