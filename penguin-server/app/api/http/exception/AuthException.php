<?php


namespace app\api\http\exception;


use Throwable;

class AuthException extends \RuntimeException
{
    public function __construct(\ArrayAccess $message, Throwable $previous = null)
    {
        $code    = $message['code'] ?? 400;
        $message = $message['message'] ?? '未知错误';
        parent::__construct($message, $code, $previous);
    }
}