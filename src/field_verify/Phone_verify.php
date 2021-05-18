<?php
namespace chenzenyang\Cirestfulapiio\field_verify;

use chenzenyang\Cirestfulapiio\field_verify\Field_verify_interface;

class Phone_verify implements Field_verify_interface {

	public function verify($value)
	{
		if (preg_match("/^09[0-9]{2}-[0-9]{3}-[0-9]{3}$/", $value))  	// 09xx-xxx-xxx
		{
	        return TRUE;
	    }
	    elseif (preg_match("/^09[0-9]{2}-[0-9]{6}$/", $value))  		// 09xx-xxxxxx
	    {
	        return TRUE;
	    }
	    elseif (preg_match("/^09[0-9]{8}$/", $value))  				// 09xxxxxxxx
	    {
	        return TRUE;
	    }
	    else
	    {
	        return FALSE;
	    }
	}
}