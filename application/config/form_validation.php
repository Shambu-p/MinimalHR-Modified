<?php

$config = array(
	'Employees/register_employee' => [
		[
			'field' => 'full_name',
			'label' => 'full_name',
			'rules' => 'required|regex_match[/(\w\s?)+/]|max_length[100]|min_length[10]'
		],
		[
			'field' => 'email',
			'label' => 'email',
			'rules' => 'required|valid_email|is_unique[employee.email]|max_length[50]'
		],
		[
			'field' => 'salary',
			'label' => 'salary',
			'rules' => 'required|numeric'
		],
		[
			'field' => 'phone_number',
			'label' => 'phone_number',
			'rules' => 'required|trim|regex_match[/(\+2519|09)\d{8}/]|max_length[14]'
		],
		[
			'field' => 'education_level',
			'label' => 'education_level',
			'rules' => 'required|in_list[ba,bsc,beng,llb,marts,mbiol,mcomp,meng,mmath,mphys,msci,ma,msc,mba,mphil,mres,llm,phd]'
		],
		[
			'field' => 'department_id',
			'label' => 'department_id',
			'rules' => 'required|integer'
		],
		[
			'field' => 'position',
			'label' => 'position',
			'rules' => 'required|trim|regex_match[/(\w\s?)+/]|max_length[100]'
		],
		[
			'field' => 'address',
			'label' => 'address',
			'rules' => 'required'
		]
	],
	'Employees/change_password' => [
		[
			'field' => 'employee_id',
			'label' => 'employee_id',
			'rules' => 'required|integer'
		],
		[
			'field' => 'old_password',
			'label' => 'old_password',
			'rules' => 'required|max_length[20]|min_length[8]'
		],
		[
			'field' => 'new_password',
			'label' => 'new_password',
			'rules' => 'required|max_length[20]|min_length[8]'
		],
		[
			'field' => 'confirm_password',
			'label' => 'confirm_password',
			'rules' => 'required|max_length[20]|min_length[8]'
		],
	],
	'Employees/change_profile_picture' => [
		[
			'field' => 'token',
			'label' => 'token',
			'rules' => 'required'
		],
		[
			'field' => 'employee_id',
			'label' => 'employee_id',
			'rules' => 'required|integer'
		]
	],
	'Employees/profile_picture' => [
		[
			'field' => 'token',
			'label' => 'token',
			'rules' => 'required'
		]
	],
	'Employees/change_application_status' => [
		[
			'field' => 'token',
			'label' => 'token',
			'rules' => 'required'
		],
		[
			'field' => 'status',
			'label' => 'status',
			'rules' => 'required|in_list[accepted,rejected,viewed,pending]'
		],
	],
	'Department/create' => [
		[
			'field' => 'name',
			'label' => 'name',
			'rules' => 'required|regex_match[/(\w\s?)+/]|max_length[100]|min_length[10]'
		],
		[
			'field' => 'department_head',
			'label' => 'department_head',
			'rules' => 'integer|max_length[100]|min_length[10]'
		]
	],
	'Department/update' => [
		[
			'field' => 'department_id',
			'label' => 'department_id',
			'rules' => 'required|integer'
		],
		[
			'field' => 'department_name',
			'label' => 'department_name',
			'rules' => 'regex_match[/(\w\s?)+/]|max_length[100]|min_length[10]'
		],
		[
			'field' => 'department_head',
			'label' => 'department_head',
			'rules' => 'integer'
		],
	],

	'Auth/login' => [
		[
			'field' => 'email',
			'label' => 'email',
			'rules' => 'required|valid_email|max_length[50]'
		],
		[
			'field' => 'password',
			'label' => 'password',
			'rules' => 'required|max_length[20]|min_length[8]'
		]
	],
	'Auth/authorization' => [
		[
			'field' => 'token',
			'label' => 'token',
			'rules' => 'required'
		]
	]
);
