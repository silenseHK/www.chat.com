<?php


namespace app\api\controller;


use app\api\controller\base\Base;
use app\api\model\Users;
use think\Exception;
use think\Validate;

class Index extends Base
{

    /**
     * Notes: 小程序登录
     * Author: WJWL666
     * Date: 2020/1/14
     * Time: 16:22
     * @param Validate $validate
     * @return \think\response\Json
     */
    public function login(Validate $validate){
        try{
            ##验证
            $res = $validate->rule([
                'code|登录code' => 'require',
                'nickname|昵称' => 'require',
                'avatar|头像' => 'require'
            ])->check(input());
            if(!$res)throw new Exception($validate->getError());

            ##逻辑
            $info = Users::login();
            if(is_string($info))throw new Exception($info);

            ##返回
            return json(self::callback(1,'登录成功', $info));
        }catch(Exception $e){
            return json(self::callback(0,$e->getMessage()));
        }
    }

}