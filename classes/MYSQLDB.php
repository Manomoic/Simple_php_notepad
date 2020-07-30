<?php
error_reporting( E_ALL );

if (!defined('LOCALHOST') || !defined('USERROOT') || !defined('USERPASSWORD') || !defined('DBNAME')):
    define('LOCALHOST', 'localhost');
    define('USERROOT', 'manomoic');
    define('USERPASSWORD', 'manamela1000');
    define('DBNAME', 'trial');
endif;

class MYSQLDB
{
    public $connection;
	private $magic_quotes_active;
	private $real_escape_string_exists;
    public $tbl_note = "tbl_note_pad";
    
    function __construct()
    {
        $this->open_conn();
    }
    
    function open_conn()
    {
        $this->connection = mysqli_connect(LOCALHOST, USERROOT, USERPASSWORD);
        $db_select        = mysqli_select_db( $this->connection, DBNAME);
        
        if ( !$this->connection )
            trigger_error( mysqli_error($this->connection), E_USER_NOTICE );
        
        if ( !$db_select )
            trigger_error( mysqli_error($this->connection), E_USER_NOTICE );
    }
    
    function close_conn()
    {
        if ( isset($this->connection) ) {
            mysqli_free_result( $this->connection );
            mysqli_close( $this->connection );
            unset( $this->connection );
        }
    }
    
    public function sql_error() {
        return mysqli_error( $this->connection );
    }
    
    public function sql_error_no() {
        return mysqli_errno( $this->connection );
    }
    
    public function sql_query($str = '') {
        return mysqli_query( $this->connection, $str );
    }
    
    public function sql_fetch($str) {
        return mysqli_fetch_array( $str, MYSQLI_BOTH );
    }
    
    public function sql_numRow($sql) {
		return $results = mysqli_num_rows( $sql );
	}
    
    public function sql_affectedRow() {
		return $results = mysqli_affected_rows();
	}
    
    public function escape_value( $value ) {
		if( $this->real_escape_string_exists ) { // PHP v4.3.0 or higher
			// undo any magic quote effects so mysql_real_escape_string can do the work
			if( $this->magic_quotes_active ) { $value = stripslashes(  $value ); }
			$value = mysqli_real_escape_string( $value );
		} else { // before PHP v4.3.0
			// if magic quotes aren't already on then add slashes manually
			if( !$this->magic_quotes_active ) { $value = addslashes( $value ); }
			// if magic quotes are active, then the slashes already exist
		}
		return $value;
	}
}

$db_connection = new MYSQLDB();
?>