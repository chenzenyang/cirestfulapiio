<?php

require_once 'field_verify/Email_verify.php';

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