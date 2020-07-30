<?php
/* 
========================================== 
Insert Script
========================================== 
*/
require_once('classes/MYSQLDB.php');
require_once('classes/USER.php');

if (isset($_POST['note']) || isset($_POST['note']) != '')
{   
    $note = $db_connection->escape_value( filter_input(INPUT_POST,'note') );
    
    if ( empty($note) ) {
        echo 'Please Make Sure That The Note Field Is Not Empty.';
        exit();
        
    } elseif ( strlen($note) >= 100 ) {
        echo 'The Number Of Characters Allowed Are 100.';
        exit();
        
    } else {
        if ( $user->insert_user_note( $note ) )
            return true;
        
        exit();
    }
}



/* 
========================================== 
Update Script
========================================== 
*/ //isset($_POST['updateNote']) ||
if (isset($_GET['id'])) {
$note_id = $_GET['id']; var_dump($note_id);
$updateNote = $db_connection->escape_value( filter_input(INPUT_POST,'updateNote') );

/*if ( $updateNote == '' ) {
echo 'Please Make Sure That The Note Field Is Not Empty.';
exit();

} else if ( strlen($updateNote) >= 100 ) {
echo 'The Number Of Characters Allowed Are 100.';
exit();

} else {*/
//$user->update_user_note($updateNote);
$sql_results = $db_connection->sql_query("UPDATE ".$user->tbl_note." SET note ='".$updateNote."', created = NOW() WHERE id='".$note_id."' ORDER BY created DESC");
echo $sql_results;
//}
}

/* 
========================================== 
Delete Script
========================================== 
*/
if ( isset($_GET['deteleteID']) ) {
    $note_id    = '';
    
    $sql_results = $db_connection->sql_query("SELECT * FROM ".$user->tbl_note." ORDER BY created DESC");

    if ( $db_connection->sql_numRow($sql_results) > 0 ) {
        while( $row = $db_connection->sql_fetch($sql_results) ):

            $note_id .= $row['id'];
            
            $sql_delete = $db_connection->sql_query("DELETE FROM ".$user->tbl_note." WHERE id ='".$note_id."' LIMIT 1");

            if ( !$sql_delete ):
                trigger_error( mysqli_error($this->connection), E_USER_NOTICE );
                exit();
            else:
                echo 'Removed';
                exit();
            endif;

        endwhile;
    }
}
?>
