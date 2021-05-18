<?php
namespace chenzenyang\Cirestfulapiio\field_verify;

use chenzenyang\Cirestfulapiio\field_verify\Field_verify_interface;

class Birthday_verify implements Field_verify_interface {

	public function verify($value)
	{
		if ( ! strtotime($value))
		{
			return FALSE;
		}
		else
		{
			return TRUE;
		}
	}
}