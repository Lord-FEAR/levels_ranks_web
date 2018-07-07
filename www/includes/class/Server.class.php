<?php
/**
 * @author LordF
 */

    class Server {
        public $name, $host, $dbName, $login, $pass, $port;

        public function __construct( $name, $host, $dbName, $login, $pass, $port){
            $this->name = $name;
            $this->host = $host;
            $this->dbName = $dbName;
            $this->login = $login;
            $this->pass = $pass;
            $this->port = $port;
        }

        function getName(){
            return $this->name;
        }
        function setName($name){
            $this->name = $name;
        }

        function getHost(){
            return $this->host;
        }
        function setHost($host){
            $this->host = $host;
        }

        function getDbName(){
            return $this->dbName;
        }
        function setDbName($dbName){
            $this->dbName = $dbName;
        }

        function getLogin(){
            return $this->login;
        }
        function setLogin($login){
            $this->login = $login;
        }

        function getPass(){
            return $this->pass;
        }
        function setPass($pass){
            $this->pass = $pass;
        }

        function getPort(){
            return $this->port;
        }
        function setPort($port){
            $this->port = $port;
        }
        
        function countRow() {
            $pdo = $this->connect();
            $stmt = $pdo->query("SELECT COUNT(*) as count FROM lvl_base")->fetchColumn();
            unset($pdo);
            return $stmt;
        }

        function allPlayers(){
            $pdo = $this->connect();
            $stmt = $pdo->query("SELECT COUNT(*) as count FROM lvl_base")->fetchColumn();
            unset($pdo);
            return $stmt;
        }
        
        function top() {
            $pdo = $this->connect();
            $stmt = $pdo->prepare('SELECT * FROM lvl_base ORDER BY value DESC LIMIT 10');
            $stmt->execute();
            unset($pdo);
            $stat = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $stat;
        }
        
        function lr_array($page, $recordOnPage) {
            $pdo = $this->connect();
            $stmt = $pdo->prepare('SELECT * FROM lvl_base ORDER BY value DESC LIMIT :page, :recordOnPage');
            $stmt->bindParam(':page', $page, PDO::PARAM_INT);
            $stmt->bindParam(':recordOnPage', $recordOnPage, PDO::PARAM_INT);
            $stmt->execute();
            
            unset($pdo);
            $stat = $stmt->fetchAll();
            return $stat;
        }
        
        function searchPlayer($data, $metod) {
            $pdo = $this->connect();
            if($metod == "steam"){
                $stmt = $pdo->prepare('SELECT name, steam FROM lvl_base WHERE steam = :steam');
                $stmt->execute(array(':steam' => $data));
                unset($pdo);
		$stat = $stmt->fetch(PDO::FETCH_LAZY);
		return $stat;
            }else{
                $name = "%$data%";
                $stmt = $pdo->prepare('SELECT name, steam FROM lvl_base WHERE name LIKE ?');
                $stmt->execute(array($name));
		unset($pdo);
		$stat = $stmt->fetchAll(PDO::FETCH_ASSOC);
		return $stat;
            }
        }

        function connect(){
            $host = $this->host;
            $charset = "UTF8";
            $db = $this->dbName;
            $user = $this->login;
            $pass = $this->pass;
            $port = $this->port;
            $dsn = "mysql:host=$host;port=$port;dbname=$db;charset=$charset";
            $opt = array(
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
            );
            try {
                $pdo = new PDO($dsn, $user, $pass, $opt);
                return $pdo;
            } catch (Exception $e) {
                echo '<br><b>Соединение оборвалось: ' , $e->getMessage() , '</b><br><a href="mailto:', EMAIL ,'">При повторной ошибке сообщите администратору!</a>';
                exit();
            }
            
        }
    }
?>