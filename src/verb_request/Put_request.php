<?php
namespace chenzenyang\Cirestfulapiio\verb_request;

use chenzenyang\Cirestfulapiio\verb_request\Verb_request_interface;

class Put_request implements Verb_request_interface {

	public function receive($obj, $field)
	{
		return $obj->put($field);
	}
}