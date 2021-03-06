<?php
require_once( plugin_dir_path( __FILE__ ) . 'utils.php');

class Database {
    private  $dbName = 'testDB';
    private  $dbHost = 'localhost';
    private  $port = '8889';
    private  $dbUsername = 'root';
    private  $dbUserPassword = 'root';

    // private  $dbName = 'dbs1031742';
    // private  $dbHost = 'db5001205940.hosting-data.io';
    // private  $port = '8889';
    // private  $dbUsername = 'dbu443908';
    // private  $dbUserPassword = 'rFpSnqamDlcvzzIdrqiA';
    
    private  $cont = null;
    private  $db;

    function __construct() {
        $this->db = $this->connect();
    }

    public  function connect(){
        if ( null == $this->cont ){
            try {
                $arg = "mysql:host=" . $this->dbHost . ";dbname=" . $this->dbName . ";port=" . $this->port;
                // $arg = "mysql:host=" . $this->dbHost . ";dbname=" . $this->dbName;
                $this->cont = new PDO (
                    $arg, $this->dbUsername, $this->dbUserPassword);

            }catch (PDOException $e) {
                die ($e -> getMessage());
            }
        }
        return $this->cont;
    }
    public function disconnect (){
        $this->cont = null ;
    }

    public function getCurrentUser() {
        $id = get_current_user_id();
        $sql = "SELECT * FROM wp_users where ID=?"; 
        return $this->query($sql, [$id])[0];
    }

    public function getUserById($id) {
        $sql = "SELECT * FROM wp_users where ID=?"; 
        $res = $this->query($sql, [$id]);
        if (isset($res) && count($res)>0){
            return $res[0];
        }
        else {
            return null;
        }
    }
    /**
     * @param sql: sql string query
     * @param params: array of query parameters filling ? marks in sql query
     */
    public function query($sql, array $params){
        $q = $this->db->prepare($sql);
        $q->execute($params); 
        $data = $q->fetchAll();
        return $data;
    }

    public function getMetierByUserId($id){
        global $METIERS;
        global $costumFieldsId;
        $metier = "";
        $sql = "SELECT value FROM wp_bp_xprofile_data where user_id=? and field_id=?"; 
        $results = $this->query($sql, [$id,$costumFieldsId['metier']]);
        foreach($results as $result){
            if (isset($result['value']) && array_search($result['value'], $METIERS) >= 0){
                $metier = $result['value'];
                break;
            }
        }
        return $metier;
    }

    public function getListOfTables() {
        $sql = "SELECT table_name FROM user_tables"; 
        return $this->query($sql,[]);
    }
}

?>