<?php
namespace chenzenyang\Cirestfulapiio;

use chenzenyang\Cirestfulapiio\field_verify\Email_verify;
use chenzenyang\Cirestfulapiio\field_verify\Birthday_verify;
use chenzenyang\Cirestfulapiio\field_verify\Phone_verify;

class Simple_verify_factory {
	
	public function create_verify($verify)
	{
		switch ($verify)
		{
			case 'EMAIL':
				return new Email_verify();

			case 'BIRTHDAY':
				return new Birthday_verify();

			case 'PHONE':
				return new Phone_verify();

			default:
				return new Null_verify();
		}
	}
}