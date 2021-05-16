<?php

require_once 'Verb_request_interface.php';

class Get_request implements Verb_request_interface {

	public function receive($obj, $field)
	{
		return $obj->get($field);
	}
}