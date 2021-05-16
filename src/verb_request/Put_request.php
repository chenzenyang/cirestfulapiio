<?php

require_once 'Verb_request_interface.php';

class Put_request implements Verb_request_interface {

	public function receive($obj, $field)
	{
		return $obj->put($field);
	}
}