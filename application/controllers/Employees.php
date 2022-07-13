<?php

class Employees extends API_Controller {

	function __construct() {
		parent::__construct();
		$this->load->module("user");
	}

	function register_employee_post() {
		try{
			$this->response($this->user->register_employee(), 200);
		}catch(Exception $ex){
			$this->response(["message" => $ex->getMessage()], 200);
		}
	}

	function change_password_post() {
		try{
			$this->response($this->user->change_password(), 200);
		}catch(Exception $ex){
			$this->response(["message" => $ex->getMessage()], 200);
		}
	}

	function profile_picture_get($employee_id) {
		try{
			$this->user->profile_picture($employee_id);
		}catch(Exception $ex){
			$this->response(["message" => $ex->getMessage()], 200);
		}
	}

	function document_get($employee_id) {
		try{
			$this->user->document($employee_id);
		}catch(Exception $ex){
			$this->response(["message" => $ex->getMessage()], 200);
		}
	}

	function change_profile_picture_post() {
		try{
			$this->response($this->user->change_profile_picture(), 200);
		}catch(Exception $ex){
			$this->response(["message" => $ex->getMessage()], 200);
		}
	}

	function employee_detail_post() {
		try{
			$this->response($this->user->employee_detail(), 200);
		}catch(Exception $ex){
			$this->response(["message" => $ex->getMessage()], 200);
		}
	}

	function employee_list_post() {
		try{
			$this->response($this->user->employee_list(), 200);
		}catch(Exception $ex){
			$this->response(["message" => $ex->getMessage()], 200);
		}
	}

	function change_application_status_post() {
		try{
			$this->response($this->user->change_application_status(), 200);
		}catch(Exception $ex){
			$this->response(["message" => $ex->getMessage()], 200);
		}
	}

	function application_detail_post() {
		try{
			$this->response($this->user->application_detail(), 200);
		}catch(Exception $ex){
			$this->response(["message" => $ex->getMessage()], 200);
		}
	}

	function application_list_post() {
		try{
			$this->response($this->user->application_list(), 200);
		}catch(Exception $ex){
			$this->response(["message" => $ex->getMessage()], 200);
		}
	}

	function check_application_get() {
		try{
			$this->response($this->user->check_application(), 200);
		}catch(Exception $ex){
			$this->response(["message" => $ex->getMessage()], 200);
		}
	}

	function apply_for_vacancy_post() {
		try{
			$this->response($this->user->apply_for_vacancy(), 200);
		}catch(Exception $ex){
			$this->response(["message" => $ex->getMessage()], 200);
		}
	}

	function forgot_password_post() {
		try{
			$this->response($this->user->forgot_password(), 200);
		}catch(Exception $ex){
			$this->response(["message" => $ex->getMessage()], 200);
		}
	}

	function verify_user_post() {
		try{
			$this->response($this->user->verify_user(), 200);
		}catch(Exception $ex){
			$this->response(["message" => $ex->getMessage()], 200);
		}
	}

	function change_status_post() {
		try{
			$this->response($this->user->change_status(), 200);
		}catch(Exception $ex){
			$this->response(["message" => $ex->getMessage()], 200);
		}
	}

	function recover_password_post() {
		try{
			$this->response($this->user->recover_password(), 200);
		}catch(Exception $ex){
			$this->response(["message" => $ex->getMessage()], 200);
		}
	}

	function add_address_post() {
		try{
			$this->response($this->user->add_address(), 200);
		}catch(Exception $ex){
			$this->response(["message" => $ex->getMessage()], 200);
		}
	}

	function edit_address_post() {
		try{
			$this->response($this->user->edit_address(), 200);
		}catch(Exception $ex){
			$this->response(["message" => $ex->getMessage()], 200);
		}
	}

	function delete_address_post() {
		try{
			$this->response($this->user->delete_address(), 200);
		}catch(Exception $ex){
			$this->response(["message" => $ex->getMessage()], 200);
		}
	}

	function login_post() {
		try{
			$this->response($this->user->login(), 200);
		}catch(Exception $ex){
			$this->response(["message" => $ex->getMessage()], 200);
		}
	}

	function authorization_post(){
		return $this->user->authorization();
	}

}
