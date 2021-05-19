<?php
namespace chenzenyang\Cirestfulapiio\verb_request;

use chenzenyang\Cirestfulapiio\verb_request\Verb_request_interface;

class Bearer_request implements Verb_request_interface {

	public function receive($obj, $field)
	{
		if ($field == 'login_token')
		{
			$header = $obj->input->get_request_header('Authorization', TRUE);
			
			if ( ! isset($header) OR empty($header))
				$header = $_SERVER['REDIRECT_HTTP_AUTHORIZATION'];

			$token = sscanf($header, $obj->header_prefix . ' %s');

			if (is_array($token) AND isset($token))
				return $this->_token_tf_user($obj, $token[0]);
		}
		
		return NULL;
	}

	private function _token_tf_user($obj, $value)
	{
		$user = $obj->token_obj->verify_token($value);

		if ($user['status'] AND $user['user_id'] != NULL)
		{
			$obj->request_data['user_id'] 		= $user['user_id'];
			$obj->request_data['create_time'] 	= $user['create_time'];
			$obj->request_data['expired_time']  = $user['expired_time'];

			return $value;
		}
		else
		{
			return NULL;
		}
	}
}