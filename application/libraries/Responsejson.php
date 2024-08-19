<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Responsejson
{

  protected $res;

  function __construct() {
    $this->res =& get_instance();
  }

  public function send_response_json($data, $response_code = null)
  {
    $this->res->output
      ->set_content_type('application/json')
      ->set_output(json_encode($data));
    if (!is_null($response_code)) {
      http_response_code($response_code);
    }
  }
}
