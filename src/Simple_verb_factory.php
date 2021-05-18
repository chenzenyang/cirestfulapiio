<?php
namespace chenzenyang\Cirestfulapiio;

use chenzenyang\Cirestfulapiio\verb_request\Bearer_request;
use chenzenyang\Cirestfulapiio\verb_request\Get_request;
use chenzenyang\Cirestfulapiio\verb_request\Post_request;
use chenzenyang\Cirestfulapiio\verb_request\Put_request;
use chenzenyang\Cirestfulapiio\verb_request\Del_request;
use chenzenyang\Cirestfulapiio\verb_request\Img_request;
use chenzenyang\Cirestfulapiio\verb_request\File_request;

class Simple_verb_factory {
	
	public function create_verb($verb)
	{
		switch ($verb)
		{
			case 'BEARER':
				return new Bearer_request();

			case 'GET':
				return new Get_request();

			case 'POST':
				return new Post_request();

			case 'PUT':
				return new Put_request();

			case 'DEL':
				return new Del_request();

			case 'FILE':
				return new File_request();

			case 'IMG':
				return new Img_request();

			default:
				return new Null_request();
		}
	}
}