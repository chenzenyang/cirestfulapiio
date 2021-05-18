<?php
namespace chenzenyang\Cirestfulapiio\field_verify;

use chenzenyang\Cirestfulapiio\field_verify\Field_verify_interface;

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