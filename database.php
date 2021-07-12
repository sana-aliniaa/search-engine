<?php

class Database {
    private  $dbName = 'testDB';
    private  $dbHost = 'localhost';
    private  $port = '8889';
    private  $dbUsername = 'root';
    private  $dbUserPassword = 'root';
    private  $cont = null;
    private  $db;

    function __construct() {
        $this->db = $this->connect();
    }

    public  function connect(){
        if ( null == self::$cont ){
            try {
                $arg = "mysql:host=" . self::$dbHost . ";dbname=" . self::$dbName . ";port=" . self::$port;
                self::$cont = new PDO (
                    $arg, self::$dbUsername, self::$dbUserPassword);

            }catch (PDOException $e) {
                die ($e -> getMessage());
            }
        }
        return self::$cont;
    }
    public  function disconnect (){
        self::$cont = null ;
    }

    public  function getCurrentUser() {
        $sql = "SELECT * FROM wp_users where ID=?"; 
        $result = $this->db->prepare($sql); 
        $result->execute([get_current_user_id()]); 
        return $result->fetch(); 
    }
}

?>