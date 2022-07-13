<?php

class AuthModel extends CI_Model {

	private String $SecretKey = "my_secret_key";

	/**
	 * @param $auth_object
	 * @param string $token
	 *            authentication string to be decrypted
	 * @return array
	 *            returns the decrypted user from authentication string
	 * @throws Exception
	 */
	function checkAuth($auth_object, string $token) {

		$result = (array) $auth_object->validateToken($this->input->post("token"));
		if(!$result["status"]){
			throw new Exception($result["message"]);
		}

		return (array) $result["data"];

	}

	/**
	 * @param $data
	 * 			array of user properties to be encrypted
	 * @return string
	 * 			returns the decrypted user properties as json encrypted string
	 */
	function tokenStringGenerator($data) {

		$jwt = new JWT();
		return $jwt->encode($data, $this->SecretKey, 'HS256');

	}

}
