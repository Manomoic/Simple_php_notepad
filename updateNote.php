<?php

if (isset($_POST['valueOfNote'])) {
    
    require_once('classes/MYSQLDB.php');
    require_once('classes/USER.php');
    
    $noteToUpdate = $db_connection->escape_value(filter_input(INPUT_POST, 'valueOfNote'));
    
    if ( empty($noteToUpdate) ) {
        echo 'Please Make Sure That The Note Field To Be Updated Is Not Empty.';
        exit();
        
    } else {
        
        $sql_select = $db_connection->sql_query("SELECT * FROM ".$db_connection->tbl_note);
        
        if ( $db_connection->sql_numRow($sql_select) > 0 ) {
            foreach( $sql_select as $row ) {
               $sql_update = $db_connection->sql_query("UPDATE ".$db_connection->tbl_note." 
               SET note = '".$noteToUpdate."', created = NOW() 
               WHERE id = '".$row['id']."' LIMIT 1");
                
                if ( !$sql_update ) {
                    trigger_error( mysqli_error($this->connection), E_USER_NOTICE );
                    exit();
                
                } else {
                    var_dump($sql_update);
                } 
            }
        }
    }
    
    exit();
}
?>