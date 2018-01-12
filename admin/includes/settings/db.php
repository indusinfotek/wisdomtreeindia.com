<?php

/**
 * Database connection class.
 * $db = new db(array(host, user, password, database));
 * $db->connect();
 * $clean = $db->clean($dirty);
 * $db->query("");
 * $db->disconnect();
 */
class db {
    private $db = array();
    private $connection;
    //public function db($args = array()) {
    public function db() {
        $this->db['server']     = 'localhost';
        $this->db['username'] 	= 'abworzlc_usrAlwa';
        $this->db['password'] 	= 'BGhqUHz)22a~';
        //$this->db['database'] 	= $args[3];             
    }

    public function connect() {
        $this->connection = mysqli_connect($this->db["server"], $this->db["username"], $this->db["password"]);
        //$this->select_db();
    }

    public function disconnect() {
        mysqli_close($this->connection);
        $this->connection = null;        
    }

    public function select_db($dbname) {
        $this->db['database'] = $dbname;
        mysqli_select_db($this->connection, $this->db['database']);
    }

    public function query($sql) {
        $this->result = mysqli_query($this->connection, $sql);   
        return $this->result;
    }

    public function is_connected() {
            return ($this->connection) ? true : false;            
    }

    public function clean($dirty) {
        if (!is_array($dirty)) {
                $dirty = ereg_replace("[\'\")(;|`,<>]", "", $dirty);
                $dirty = mysqli_real_escape_string($this->connection, trim($dirty));
                $clean = stripslashes($dirty);
                return $clean;                    
        }
        $clean = array();
        foreach ($dirty as $p=>$data) {
                $data = ereg_replace("[\'\")(;|`,<>]", "", $data);
                $data = mysqli_real_escape_string($this->connection, trim($data));
                $data = stripslashes($data);
                $clean[$p] = $data;                    
        }
        return $clean;   
    }
}
