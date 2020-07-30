<?php
require_once('classes/MYSQLDB.php');
require_once('classes/USER.php');

$output = '';
$id_count = 1;

$sql_results = $db_connection->sql_query("SELECT * FROM ".$user->tbl_note." ORDER BY created DESC");

if ( !$sql_results ):
    trigger_error( mysqli_error($this->connection), E_USER_NOTICE );
    exit();
endif;

if ( $db_connection->sql_numRow($sql_results) > 0 ) {
    
    $output .= '<table class="table table-md table-hover table-bordered table-data">';
    
    $output .= '<thead>
                   <th>#</th>
                   <th>Date</th>
                   <th>Notes</th>
                   <th width="20px">Action</th>
                </thead>
                <tbody>';
    
    foreach( $sql_results as $row ) {
        
        $output .= '<tr>';
        $output .= '<td>'.$id_count++.'</td>';
        $output .= '<td>'.$row['created'].'</td>';
        $output .= '<td>'.$row['note'].'</td>';
        $output .= '<td>
            <div class="btn-group btn-group-sm" role="group" aria-label="Basic example">
                <button type="button" class="btn btn-outline-warning" id="display_update_note" name="display_update_note" value="'.$row['id'].'" onclick="display_update_note_section('.$row['id'].')">Update</button>
            
                <button type="submit" class="btn btn-outline-danger" name="btn_note_delete" id="'.$row['id'].'" onclick="btn_note_delete()">Delete</button>
            </div>
        </td>';
    }
    
    $output .= '</tbody></table>';
} else {
    $output .= '<p class="lead"> No Available Note. Please Click The *Add Note* Button. </p>';
}


echo $output;
?>