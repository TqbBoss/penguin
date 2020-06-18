<?php


namespace app\api\http\exception;


class ParamException extends  \Exception
{
    public function __construct($message = "", $code = 0) {
        parent::__construct($message, $code, null);
    }
}