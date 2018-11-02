<?php

class Sql extends PDO{
        private $conn;

        public function __construct(){
		$this->conn = new PDO("mysql:host=localhost;dbname=graficos","root", "123456");
                $this->conn->exec("set names utf8");
        }

        private function setParams($statement, $parameters = array()){
                foreach ($parameters as $key => $value) {
                        $this->setParam($statement,$key, $value);
                }
        }

        private function setParam($statement,$key, $value){
                $statement->bindParam($key,$value);
        }

        public function query($rawQuery, $params = array()){
                $stmt = $this->conn->prepare($rawQuery);
                $this->setParams($stmt, $params);

                $stmt->execute();
               
		
//		$num_rows = $stmt->fetchColumn();

//		if($num_rows <= 0)
//			array_push($stmt,"Sem nome");
//		}

		return $stmt;
        }

        public function select($rawQuery,$params = array())
        {
                $stmt = $this->query($rawQuery, $params);
		

	
		return  $stmt->fetchAll(PDO::FETCH_ASSOC);		
				        
        }

	public function selectNome($rawQuery,$params = array())
        {
                $stmt = $this->query($rawQuery, $params);



                return  $stmt;

        }

}

?>
