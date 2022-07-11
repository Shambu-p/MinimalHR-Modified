<?php

require APPPATH . '/libraries/REST_Controller.php';
use Restserver\Libraries\REST_Controller;

class API_Controller extends REST_Controller {

	public array $auth_user = [];

	function __construct() {

		header('Access-Control-Allow-Origin: *');

		parent::__construct();

		if(strtolower($_SERVER["REQUEST_METHOD"]) == "post") {

			if(!$this->form_validation->run()) {
				$this->response(["message" => validation_errors()], 200);
			}

		}

		$this->getUserByToken();

	}

	function getUserByToken(){

		if(!isset($_POST["token"])){
			return;
		}

		$result = (array) $this->authorization_token->validateToken($_POST["token"]);
		if(!$result["status"]){
			$this->response(["message" => $result["message"]], 200);
		}

		$this->auth_user = (array) $result["data"];

	}

	/**
	 * @param string $role
	 * 		user role this case [admin, non_admin]
	 * @param boolean $respond_if_false
	 * 		if check result is required leave this field false
	 * 		but if json response is needed then true
	 * @return bool
	 */
	function authenticate(string $role, bool $respond_if_false){

		if($role == "admin" && !$this->auth_user["is_admin"]) {
			return $this->authenticationResponse($respond_if_false);
		}

		return true;

	}

	/**
	 * @param $respond_or_return
	 * @return false
	 */
	function authenticationResponse($respond_or_return) {

		if($respond_or_return){
			$this->response(["message" => "access denied!"], 200);
			return false;
		}else{
			return false;
		}

	}

	/**
	 * simple method only for this project
	 *
	 * @param $file_name
	 * 		file base name
	 * @param $field_name
	 * 		the name of field which the file is being uploaded by
	 * @param false $overwriting
	 * 		if there already exist file and you want overwrite it pass true or else false
	 * @return array|mixed|null
	 */
	function my_upload($file_name, $field_name, $overwriting = FALSE){

		$parameters = $field_name == 'documents' ? [
			'upload_path' => './uploads/documents',
			'file_name' => $file_name,
			'allowed_types' => ['zip']
		] : [
			'upload_path' => './uploads/profile_pictures',
			'file_name' => $file_name,
			'allowed_types' => ['jpg', 'png', 'ico', 'jpeg']
		];

		$parameters['overwrite'] = $overwriting;
		$parameters['max_size'] = 1000;

		$upload_data = $this->upload_file($parameters, $field_name);

		$this->upload = null;

		return $upload_data;

	}

	/**
	 * helps with file uploading
	 * you only need to provide the parameter and the field name
	 * after that it will the uploading using upload library and
	 * if there is problem while uploading the file then it will
	 * respond using REST_Controller response method.
	 * @param $parameters
	 * 		upload library configuration parameters
	 * @param $field_name
	 * 		the name of the field which the file is passed by
	 * @return array|mixed|null
	 */
	function upload_file($parameters, $field_name){

		$this->upload = null;
		$this->load->library('upload', $parameters);

		if(!$this->upload->do_upload($field_name)){
			$this->response(
				["message" => $this->upload->display_errors()],
				200
			);
		}

		return $this->upload->data();

	}

	/**
	 * respond with file content of the file found on file path
	 * which is passed on parameter named file
	 * @param $file
	 *        file address of the file to be downloaded
	 * @param $file_type
	 * 		  extension of file, or file content type
	 * 		  example image/png, image/jpg, application/pdf, application/json, application/zip
	 */
	function respond_file($file, $file_type){

		if (file_exists($file)) {
			header('Content-Description: File Transfer');
			header('Content-Type: '.$file_type);
			header('Content-Disposition: inline; filename="'.basename($file).'"');
			header('Expires: 0');
			header('Cache-Control: must-revalidate');
			header('Pragma: public');
			header('Content-Length: ' . filesize($file));
			readfile($file);
		}

		$this->response(["message" => "cannot read file"], 200);

	}

}
