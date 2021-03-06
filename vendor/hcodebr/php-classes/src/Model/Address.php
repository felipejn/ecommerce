<?php  

namespace Hcode\Model;

use \Hcode\DB\Sql;
use \Hcode\Model;

class Address extends Model 
{

	const SESSION_ERROR = "AddressError";

	public static function getCep($nrcep)
	{

		// https://viacep.com.br/ws/01001000/json/

		$ch = curl_init();
		// Curl Option 1: Link 
		curl_setopt($ch, CURLOPT_URL, "https://viacep.com.br/ws/$nrcep/json/");
		// Curl Option 2: Espera um retorno
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		// Curl Option 3: Se exige autenticação
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		// Executa e recebe retorno array com "true"
		$data = json_decode(curl_exec($ch), true);
		// Encerra o Curl
		curl_close($ch);

		return $data;

	}

	public function loadFromCep($nrcep)
	{

		$data = Address::getCep($nrcep);

		if (isset($data["logradouro"]) && $data["logradouro"])
		{

			$this->setdesaddress($data["logradouro"]);
			$this->setdescomplement($data["complemento"]);
			$this->setdesdistrict($data["bairro"]);
			$this->setdescity($data["localidade"]);
			$this->setdesstate($data["uf"]);
			$this->setdescountry("Brasil");
			$this->setnrzipcode($nrcep);

		} 

	}

	public function save()
	{

		$sql = new Sql();

		$results = $sql->select("CALL sp_addresses_save(:idaddress, :idperson, :desaddress, :desnumber, :descomplement, :descity, :desstate, :descountry, :deszipcode, :desdistrict)", array(
			":idaddress"=>$this->getidaddress(),
			":idperson"=>$this->getidperson(),
			":desaddress"=>$this->getdesaddress(),
			":desnumber"=>$this->getdesnumber(),
			":descomplement"=>$this->getdescomplement(),
			":descity"=>$this->getdescity(),
			":desstate"=>$this->getdesstate(),
			":descountry"=>$this->getdescountry(),
			":deszipcode"=>$this->getdeszipcode(),
			":desdistrict"=>$this->getdesdistrict()
		));

		if (count($results) > 0)
		{
			$this->setData($results[0]);
		}

	}

	public static function setMsgError($msg)
	{

		$_SESSION[Address::SESSION_ERROR] = $msg;

	}

	public static function getMsgError()
	{

		$msg = (isset($_SESSION[Address::SESSION_ERROR])) ? $_SESSION[Address::SESSION_ERROR] : "";

		Address::clearMsgError();

		return $msg;

	}

	public static function clearMsgError()
	{

		$_SESSION[Address::SESSION_ERROR] = NULL;

	}

}	

?>