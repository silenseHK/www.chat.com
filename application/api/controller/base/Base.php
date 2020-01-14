<?php


namespace app\api\controller\base;


use think\Controller;

class Base extends Controller
{

    /**
     * 接口回调
     * @param $status
     * @param $msg
     * @param $data
     * @return array
     */
    public static function callback($status = 1,$msg = '',$data = 0,$flag=false){
        if ($data==0 && !$flag){
            $data = new \stdClass();
        }
        //正式阶段
        #return ['status'=>$status,'msg'=>$msg,'data'=>$data];
        //测试阶段
        return ['status'=>$status,'msg'=>$msg,'data'=>$data,'request'=>request()->post()];
    }

}