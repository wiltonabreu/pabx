<?php
require_once("config.php");
//Carrega uma lista oportunidades com sales_stage = "Closed Won"

$lista = Consultas::getList();


$account_id = array();
$email_address_id = array();
$email_address = array();
$bean_id = array();
$nome = array();
$i=0;
$nome_email = array();

foreach ($lista as $result) {
   foreach ($result as $key => $value){
     $account_id[$i] = Consultas::search("select account_id from accounts_opportunities where opportunity_id = :ID",$value); 
   }
   $i++; 
}


foreach ($account_id as $result) {

       foreach ($result as $re) {
	//echo $value['account_id'] ."\n";
	$value = $re['account_id'];
	$email_address_id[$i] = Consultas::search("select email_address_id from email_addr_bean_rel WHERE bean_id =:ID AND deleted = 0",$value);
        $i++;
  }
}
//=========== Pegar os Emails ==========================================

$i=0;

foreach ($email_address_id as $result) {

  foreach ($result as $re) {
      $value = $re['email_address_id'];
      $email_address[$i] = Consultas::search("select DISTINCT email_address from email_addresses where id = :ID LIMIT 1",$value);
      $i++;
  }

}
//print_r($email_address);
//======================================================================

$i=0;
$arr = array();
$arr = array(array("bean_id" => "81eb0400-2bf1-08b6-1f80-58ab3556ea7a"));

foreach ($email_address_id as $result) {

  foreach ($result as $re) {
        $value = $re['email_address_id'];
	
	$bean = Consultas::search("select DISTINCT bean_id from email_addr_bean_rel WHERE email_address_id = :ID AND bean_module='Contacts' LIMIT 1",$value);

	if(empty($bean)){
               $bean_id[$i] = $arr;
        }else{         	
	  	$bean_id[$i] = $bean;
	}
        
	$i++;
  }

}
//print_r($bean_id); 
$i=0;

foreach ($bean_id as $result) {

  foreach ($result as $re) {
        
	$value = $re['bean_id'];
	$nome[$i] = Consultas::search("select ifnull(first_name, '') from contacts where id= :ID LIMIT 1",$value);
	$i++;
  }

}


$arrayDeEmail= array();
$i = 0;
foreach ($email_address as $email_add) {
  foreach ($email_add as $email) {

	$arrayDeEmail[$i] = array("email" => $email['email_address']);
	$i++;
  }
}

$arrayDeNome= array();
$i = 0;
foreach ($nome as $name) {
  foreach ($name as $n) {
  
	$arrayDeNome[$i] = array("name" => $n["ifnull(first_name, '')"]);
	$i++;
  }
}

$resultados = array_map(null, $arrayDeNome , $arrayDeEmail);

$token = "SEUTOKEM";
for($i=0;$i<count($resultados); $i++){

  $nome = $resultados[$i][0]['name'];
  //echo "Nome: " . $nome;
  
  $email = $resultados[$i][1]['email'];

//========================================
  $retorno = verificaEmail($email);

  if($retorno == false){

      $form_data_array = array('email'=> $email, 'nome'=>$nome);
      addLeadConversionToRdstationCrm($token, "TESTE", $form_data_array);

  }

}
//======================================== 
function verificaEmail($emailVerify){
   $filename = "logT.txt";

   if(file_exists($filename)){

 	  if( strpos(file_get_contents($filename),$emailVerify) !== false) {
  
	       echo "$emailVerify já havia sido cadastrado\n";

	       return true;
	  
	  }else{

		echo "$emailVerify não encontrado no arquivo.\n";

		$file1 = fopen("logT.txt", "a+");
		fwrite($file1, $emailVerify. "\r\n");
		fclose($file1);

		return false;
	  }
        
	
   }else{

        echo "Arquivo inexistente";
   }
 
}

?>
