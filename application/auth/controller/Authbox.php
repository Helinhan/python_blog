<?php
namespace app\auth\controller;
use think\Cookie;
use think\session;
use think\Db;
use think\Config;
class Authbox{
    public $config='';
    public $path='';
    public $uid='';
    public function __construct($path,$uid){
        $this->config=Config::get('Auth');//读取配置项
        $this->path=$path;//储存当前路径
        $this->uid=$uid;//用户id
        $this->authno();//是否开启权限验证
    }
    /*
     * 检查是否开启权限验证
     * */
    public function authno(){
        if(!!$this->config){//如果没有开启配置项
            if($this->config['AUTH_ON']){//是否开启验证
                return $this->getuserauth();
            }else{
                return true;
            }
        }else{
            return true;
        }
    }
    /*
     * 获取用户权限
     * 1、获取用户关系表
     * @$group_access 用户关系
     * 2、获取用户权限组
     * @$group 用户关系组
     * 3、获取权限详情
     * */
    public function getuserauth(){
        $group_access_table=$this->config['AUTH_GROUP_ACCESS'];//用户关系明细表
        $group_table=$this->config['AUTH_GROUP_ACCESS'];//权限组表
        $ruel_table=$this->config['AUTH_RULE'];//权限列表
        $group_access=Db::table($group_access_table)->where(['uid'=>(int)$this->uid])->select();//获取用户关系表
        $groupid=[];//权限组id列表
        $ruel=[];//权限列表
        foreach ($group_access as $k=>$val){//遍历所有的权限组id
            $groupid[]=$val['group_id'];
        }
        $group=Db::table($group_table)->where(['id'=>$groupid])->select();//获取所有得权限组

        foreach ($group as $value){//遍历所有的权限
            $ruel_id=explode(',',$value['rules']);//获取权id限列表
            foreach ($ruel_id as $rid){//遍历出所有权限
                $ruel[]=Db::table($ruel_table)->where(['id'=>$rid])->select();
            }
        }
        return $ruel;
    }
}