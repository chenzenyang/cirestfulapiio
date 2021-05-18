<?php
namespace chenzenyang\Cirestfulapiio\verb_request;

use chenzenyang\Cirestfulapiio\verb_request\Verb_request_interface;

class File_request implements Verb_request_interface {

    private $detail;

    public function receive($obj, $field)
    {
        if ( ! $obj->upload->do_upload($field))
        {
            $obj->response_data['message'] = $field . '_upload_error : ' . $obj->upload->display_errors();
            return NULL;
        }
        else
        {
            $this->detail = $obj->upload->data();
            return $obj->upload->data()['file_name'];
        }
    }

    public function show_detail()
    {
        return $this->detail;
    }
}