<?php

class Utils {

	function prepare_employee_data ($request) {

		$final_array = [
			"employee" => [],
			"address" => (array) json_decode($request["address"])
		];

		unset($request["address"]);
		unset($request["token"]);
		$vacancy_id = $request["vacancy_id"] ?? null;

		if(!$vacancy_id) {

			$final_array["account"] = [
				"email" => $request["email"],
				"status" => "active",
				"is_admin" => false,
				"password" => $this->passwordGenerator()
			];

		}else{
			unset($request["vacancy_id"]);
		}

		$final_array["employee"] = $request;
		return $final_array;

	}

	function passwordGenerator(){

		$comb = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
		$pass = array();
		$combLen = strlen($comb) - 1;
		for ($i = 0; $i < 8; $i++) {
			$n = rand(0, $combLen);
			$pass[] = $comb[$n];
		}
		return implode($pass);

	}

	function send_mail($email_object, $from, $to, $subject, $message, $recipient = []) {

		$config['protocol'] = 'sendmail';
		$config['mailpath'] = 'C:\sendmail\sendmail.exe';
		$config['charset'] = 'iso-8859-1';
		$config['wordwrap'] = TRUE;

		$config['protocol']  = 'smtp';
		$config['smtp_host'] = 'mail.appdiv.com';
		$config['smtp_port'] = '587';
		$config['smtp_user'] = 'noreply@appdiv.com';
		$config['smtp_pass'] = '-62#(mAd3g)Y';
		$config['mailtype'] = 'html';
		$config['charset']  = 'utf-8';
		$config['newline']  = "\r\n";
		$config['smtp_crypto'] = 'tls';

		$email_object->initialize($config);

		$email_object->from($from, 'Ab');
		$email_object->to($to);

		if(!empty($recipient)){
			$email_object->cc($recipient["cc"] ?? []);
			$email_object->bcc($recipient["bcc"] ?? []);
		}

		$email_object->subject($subject);
		$email_object->message($message);

		return $email_object;

	}

	function recovery_pin_message($email_object, $pin, $to){

		$from = "noreply@appdiv.com";
		$subject = "Account Verification";

		$message = '<html>
			<head>
				<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" >
			</head>
			<body>
				<div class="container text-center mt-5">
					<p>
						Some one is trying to access your HR account if it is not you ignore this email. 
						but if it is you use the following verification number to verify it is you.
					</p>					
					<h4 class="pt-4 pb-4 bg-light">verification number: '.$pin.'</h4>
				</div>
			</body>
		</html>';

		return $this->send_mail($email_object, $from, $to, $subject, $message);

	}

	function account_creation_email($email_object, $email, $password){

		$from = "noreply@appdiv.com";
		$subject = "Account Creation";

		$message = '<html>
			<head>
				<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" >
			</head>
			<body>
				<div class="container text-center mt-5">
					<h1>Account has been created for you.</h1>
					<p>you can use the following credentials to access your account</p>
					<h4 class="lead">Your Account Credential </h4>
					<h5 class="pt-4 pb-4 bg-light">Email: '.$email.' </h5>
					<h5 class="pt-4 pb-4 bg-light">password: '.$password.' </h5>
				</div>
			</body>
		</html>';

		return $this->send_mail($email_object, $from, $email, $subject, $message);

	}

}
