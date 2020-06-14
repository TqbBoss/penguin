<?php


namespace app\api;

/**
 * Class HttpCode
 * @package app\api
 * http状态
 */
class HttpCode implements \ArrayAccess
{
    private $httpStatus = [];

    public static function build($code, $message){
        return new HttpCode($code, $message);
    }

    protected function __construct($code, $message) {
        $this->httpStatus['code'] = $code;
        $this->httpStatus['message'] = $message;
    }
    public function offsetSet($offset, $value) {
        if (!is_null($offset)) {
            $this->httpStatus[$offset] = $value;
        }
    }
    public function offsetExists($offset) {
        return isset($this->httpStatus[$offset]);
    }

    public function offsetUnset($offset) {
        unset($this->httpStatus[$offset]);
    }

    public function offsetGet($offset) {
        return isset($this->httpStatus[$offset]) ? $this->httpStatus[$offset] : null;
    }
}