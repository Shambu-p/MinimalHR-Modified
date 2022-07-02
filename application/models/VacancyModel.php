<?php

class VacancyModel extends CI_Model {

	private String $table_name = "vacancy";

	function __construct(){
		parent::__construct();
	}

	function createVacancy($request){

		$this->db->insert($this->table_name, [
			"position" => $request["position"],
			"salary" => $request["salary"],
			"description" => $request["description"],
			"start_date" => $request["start_date"],
			"end_date" => $request["end_date"],
			"status" => $request["status"],
			"updated_by" => $request["employee_id"],
			"department_id" => $request["department_id"]
		]);

		$request["id"] = $this->db->insert_id();

		return $request;

	}

	function singleVacancy($id){

		$result = $this->db->get_where($this->table_name, ["id" => $id]);

		if(!$result->num_rows()){
			return [];
		}

		$vacancy = $result->result_array()[0];

		$updater_result = $this->db->get_where("employee", ["id" => $vacancy["updated_by"]]);
		$vacancy["updated_by"] = $updater_result->num_rows() ? $updater_result->result_array()[0] : [];
		return $vacancy;

	}

	function getVacancies($request){

		$query = $this->db->select("*")->from($this->table_name);

		if(isset($request["status"])){
			$query->like("status", $request["status"]);

			if($request["status"] == "open"){
				$query->where("end_date >", "current_timestamp()", true);
			}else{
				$query->where("end_date <", "current_timestamp()", true);
			}
		}

		if(isset($request["department_id"])){
			$query->like("department_id", $request["department_id"]);
		}

		if(isset($request["position"])){
			$query->like("position", $request["position"]);
		}

		return $query->get()->result_array();

	}

	function updateVacancy($request){

		$this->db->update($this->table_name, [
			"position" => $request["position"],
			"salary" => $request["salary"],
			"description" => $request["description"],
			"start_date" => $request["start_date"],
			"end_date" => $request["end_date"],
			"status" => $request["status"],
			"updated_by" => $request["updated_by"],
			"department_id" => $request["department_id"]
		],
		["id" => $request["vacancy_id"]]);

		return $request;

	}

	function deleteVacancy($request){

	}

}
