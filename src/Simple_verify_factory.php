<?php
namespace chenzenyang\Cirestfulapiio;

use chenzenyang\Cirestfulapiio\field_verify\Email_verify;

class Simple_verify_factory {
	
	public function create_verify($verify)
	{
		switch ($verify)
		{
			case 'EMAIL':
				return new Email_verify();

			default:
				return new Null_verify();
		}
	}
}