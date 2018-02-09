<?php
namespace app\auth\controller;
use app\auth\controller\Authbox;
class Auth{
    public static function check($path,$uid){
        return new Authbox($path,$uid);
    }
}