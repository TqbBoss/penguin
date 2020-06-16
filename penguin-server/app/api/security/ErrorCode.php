<?php


namespace app\api\security;


class ErrorCode
{
    public static $OK = 200;

    public static $IllegalAesKey = -41001;
    public static $IllegalIv = -41002;
    public static $IllegalBuffer = -41003;
    public static $DecodeBase64Error = -41004;

    public static $AuthFail = -1;

    public static $ParamError = -30001;
    public static $AddCartFail = -30002;
}