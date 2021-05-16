<?php
require_once 'Field_verify_interface.php';

class Email_verify implements Field_verify_interface {

	public function verify($obj, $value)
	{
		if ( ! filter_var($value, FILTER_VALIDATE_EMAIL))
		{
			return FALSE;
		}
		else
		{
			return TRUE;
		}
	}
}