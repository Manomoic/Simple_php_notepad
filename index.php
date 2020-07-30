<?php
require_once('classes/MYSQLDB.php');
require_once('classes/USER.php');

$output     = '';
$id_count   = 1;

$sql_results = $db_connection->sql_query("SELECT * FROM ".$user->tbl_note." ORDER BY created DESC");

if ( !$sql_results ):
    trigger_error( mysqli_error($this->connection), E_USER_NOTICE );
    exit();
endif;

foreach( $sql_results as $dataNote) {
    $output .= $dataNote['id'];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Notepad</title>
    <link rel="stylesheet" href="css/bootstrap.css" type="text/css" />
</head>
<body class="bg-light">
    
    <div class="nav-scroller bg-white shadow-sm">
        <nav class="nav nav-underline">
            <a href="" class="nav-link"> Notepad </a>
            <a class="nav-link ml-auto">
                Notes
                <!-- If no notes captured, the hide the link -->
                <span class="badge badge-pill bg-light align-text-bottom">
                    <!-- Number Of All Notes In The DB -->
                    <?php echo htmlspecialchars($user->total_notes());?>
                </span>
            </a>
        </nav>
    </div>
    
    <main class="container" role="main">
        <div class="d-flex align-items-center p-3 my-3 text-white-50 bg-dark rounded shadow-sm">
            <div class="lh-100">
              <h6 class="mb-0 text-white lh-100 lead">Manomoic</h6>
              <small>
                  <!-- User IP Address Goes Here -->
              </small>
            </div>
        </div>
        
        
        <!-- Editor Section -->
        <section class="mt-2 mb-4 p-2 shadow bg-light" id="update_section">
            <div id="message_edit" class="text-center lead"></div>
            <form onsubmit="return false;">
                <div class="form-row justify-content-center">
                    <div class="col-auto">
                        <textarea type="text" id="update_note" name="update_note" class="form-control" placeholder="Edit Note" cols="80" rows="1"></textarea>
                    </div>
                    
                    <div class="col-auto">
                        <button type="button" class="btn btn-outline-warning" id="btn_update_note">Update</button>
                    </div>
                </div>
            </form>
        </section>
        
        <!-- Modal Trigger Button -->
        <button class="btn btn-outline-secondary float-right btn_add_notes" type="button" data-toggle ="modal" data-target ="#addNoteModal"> Add Note </button>
        
        <!-- Modal Section -->
        <div class="modal fade" id="addNoteModal" tabindex ="-1" role ="dialog" aria-labelledby ="addNoteModalLabel" aria-hidden ="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title lead" id="addNoteModalLabel">
                            New Note
                        </h5>
                        
                        <button class="close" type="button" data-dismiss ="modal" aria-label="close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    
                    <div class="modal-body">
                        <span id="message" class="text-muted text-center"></span>
                        <form class="text-muted" onsubmit="return false;">
                            <div class="form-group">
                                <textarea type="text" id="note" name="note" class="form-control" placeholder="<?php echo date('jS \of F h:i:s A'); ?>"></textarea>
                            </div>
                        </form>
                    </div>
                    
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-danger btn_close_note" data-dismiss="modal">Close</button>
                        
                        <input type="submit" class="btn btn-outline-success btn_save_note" value="Save" />
                    </div>
                </div>
            </div>
        </div>
        <!-- /Modal Section -->
        
        <!-- Table To View All Notes In The DB -->
        <br /><br />
        <div class="d-flex align-items-center p-3 my-3 text-dark-50 bg-white rounded shadow-sm">
            <div class="table-responsive">
               <table class="table table-md table-hover table-bordered table-data">
                  <?php if ( $db_connection->sql_numRow($sql_results) > 0 ):?>
                  <thead>
                      <th>#</th>
                      <th>Date</th>
                      <th>Notes</th>
                      <th width="20px">Action</th>
                  </thead>
                  
                  <tbody>
                      <?php foreach( $sql_results as $row ):?>
                          <tr>
                              <td><?php echo htmlspecialchars($id_count++);?></td>
                              <td id="<?php echo htmlspecialchars($row['id']);?>">
                              <?php echo htmlspecialchars($row['created']);?></td>
                              <td><?php echo htmlspecialchars($row['note']);?></td>
                              <td>
                                  <div class="btn-group btn-group-sm" role="group" aria-label="Basic example">
                                      <button type="button" class="btn btn-outline-warning">Update</button>
                                      
                                      <button type="submit" class="btn btn-outline-danger" id="btn_note_delete" >Delete</button>
                                  </div>
                              </td>
                          </tr>
                      <?php endforeach;?>
                  </tbody>
                  
                  <?php else:?>
                      <div class="lead"> No Available Note. Please Click The *Add Note* Button. </div>
                  <?php endif;?>
               </table>
            </div>
        </div>
    </main>

    <script type="text/javascript" src="js/jquery.js"></script>
    <script type="text/javascript" src="js/bootstrap.js"></script>
    <script type="text/javascript">
         
        function btn_note_delete(delete_id) {
            
            $.ajax({
                url: "processingScript.php",
                type: "POST",
                data: {delete_id:delete_id},
                success: function(data) {
                    $('#display_results').html(data);
                }
            });
        }
        
        $(document).ready(function() {

            $('.btn_save_note').on('click', function() {
                var note = $('#note').val();
                var massage = $('#message');
                
                if ( note == '' ) {
                    massage.html('Please Make Sure That The Note Field Is Not Empty.');
                    console.log('Please Make Sure That The Note Field Is Not Empty.');
                    return false;
                    
                } else if ( note.length >= 100 ) {
                    massage.html('The Number Of Characters Allowed Are 100.');
                    console.log('The Number Of Characters Allowed Are 100.');
                    return false;
                    
                } else {
                    $.post("processingScript.php", {note:note}, function(data) {
                        $('#display_results').html(data);
                        console.log( data );
                        $('#note').val('');
                        //document.location.reload();
                    });
                }
            });
            
            $('#btn_update_note').on('click', function() {
                
                var valueOfNote = $('#update_note').val();
                
                if (valueOfNote == '') {
                    $('#message_edit').html('Please Make Sure That The Note Field To Be Updated Is Not Empty.');
                    console.log('Please Make Sure That The Note Field To Be Updated Is Not Empty.');
                    return false;
                }
                
            });
            
            $('.btn_close_note').on('click', function() {
                $('#note').val('');
                $('#message').html('');
                $('.btn_save_note').attr('disabled', false);
                console.log('Modal Contents Cleared.');
            });
        });
    </script>
</body>
</html>