<?php


namespace app\api\model;


class ResultReport
{
    public $result = false;

    public  $data = '';

    public  $message = '';

    public function __construct($result, $data = '', $message = '')
    {
        $this->$result = $result;
        $this->$data = $data;
        $this->$message = $message;
    }
}