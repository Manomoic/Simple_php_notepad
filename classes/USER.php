<?php
include_once('classes/MYSQLDB.php');

$conn = $db_connection;
    
class USER extends MYSQLDB
{
    public $connection;
    public $tbl_note = "tbl_note_pad";
    
    function __construct() {
        global $conn;
        $this->connection = $conn;
    }
    
    public function total_notes() {
        
        $sql = $this->connection->sql_query("SELECT 
        COUNT(id) 
        FROM ".$this->tbl_note);
                
        if ( !$sql ) {
            trigger_error( mysqli_error($this->connection), E_USER_NOTICE );
            exit();
            
        } else {

            while( $row = $this->connection->sql_fetch($sql) ):
                if ( $row[0] )
                    return $row[0];
                elseif (empty($row[0]))
                    return '0';
                else
                    return '0';
            endwhile;
            
            return true;
        }
    }
    
    public function insert_user_note($note) {
        
        $sql_insert = $this->connection->sql_query("INSERT INTO ".$this->tbl_note." (note,created,ip) VALUES ('".$note."', NOW(), '".$_SERVER['SERVER_NAME']."')");
        
        if ( !$sql_insert ):
            trigger_error( mysqli_error($this->connection), E_USER_NOTICE );
            exit();
        else:
            return true;
        endif;
    }
    
    public function update_user_note($note) {
        
        $ip = $this->get_IP_address();
        
        $sql = $this->connection->sql_query("SELECT id FROM ".$this->tbl_note);
        
        if ( !$sql ) {
            trigger_error( mysqli_error($this->connection), E_USER_NOTICE );
            exit();
            
        } else {
            if ($this->connection->sql_numRow($sql) > 0) {
                while( $row = $this->connection->sql_fetch($sql) ):
                    $sql_update = $this->connection->sql_query("UPDATE ".$this->tbl_note." SET note ='".$note."', ip ='".$ip."', created = NOW() WHERE id ='".$row['id']."' ORDER BY created DESC LIMIT 1");
                
                    if ( !$sql_update ):
                        trigger_error( mysqli_error($this->connection), E_USER_NOTICE );
                        exit();
                    else:
                        return true;
                    endif;
                endwhile;
            } else {
                return false;
            }
        }
        return true;
    }
    
    public function get_IP_address()
    {
        foreach (array('HTTP_CLIENT_IP',
                       'HTTP_X_FORWARDED_FOR',
                       'HTTP_X_FORWARDED',
                       'HTTP_X_CLUSTER_CLIENT_IP',
                       'HTTP_FORWARDED_FOR',
                       'HTTP_FORWARDED',
                       'REMOTE_ADDR',
                       'SERVER_NAME') as $key){
            
            if (array_key_exists($key, $_SERVER) === true){
                foreach (explode(',', $_SERVER[$key]) as $IPaddress){
                    $IPaddress = trim($IPaddress); // Just to be safe

                    if (filter_var($IPaddress,
                                   FILTER_VALIDATE_IP,
                                   FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)
                        !== false) {

                        return $IPaddress;
                    }
                }
            }
        }
    }
    
    /** Dump arbitrary values directly to the screen.
    */
    public function trace()
    {
        $array = func_get_args();
        $trace = debug_backtrace();

        foreach($array as $x)
        {
            echo "<br><pre>\n";
            var_dump($x);
            echo "Line {$trace[0]['line']} in {$trace[0]['file']}";
            echo "</pre><hr>\n";
        }
        flush();
    }
}

$user = new USER();
?>