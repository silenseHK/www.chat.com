<?php


namespace app\api\model;


use think\Exception;
use think\Model;
use we_chat\Event;

class Users extends Model
{

    protected $autoWriteTimestamp = false;

    protected $insert = ['login_time', 'create_time'];

    /**
     * Notes: 自动完成--生成登录时间
     * Author: WJWL666
     * Date: 2020/1/14
     * Time: 16:18
     * @return int
     */
    public function setLoginTimeAttr(){
        return time();
    }

    /**
     * Notes: 自动完成--生成创建时间
     * Author: WJWL666
     * Date: 2020/1/14
     * Time: 16:18
     * @return int
     */
    public function setCreateTimeAttr(){
        return time();
    }

    /**
     * Notes: 小程序登录
     * Author: WJWL666
     * Date: 2020/1/14
     * Time: 16:21
     * @return array|string
     */
    public static function login(){
        try{
            ##接收参数
            $code = input('post.code','','strip_tags,trim');
            $nickname = input('post.nickname','','addslashes,strip_tags,trim');
            $avatar = input('post.avatar','','strip_tags,trim');

            ##获取openid
            $result = Event::getOpenId($code);
            if($result && isset($result['session_key']) && isset($result['openid'])){
                $model = new self();
                ##检查是否已注册
                $info = $model->where(['openid'=>$result['openid']])->field('id,nickname,avatar')->find();
                $data = compact('nickname','avatar');
                if(!$info){##没有注册
                    $data['openid'] = $result['openid'];
                    $res = $model->isUpdate(false)->save($data);
                    if($res === false)throw new Exception('用户注册失败');
                    $user_id = $model->getLastInsID();
                }else{##更新用户数据
                    $res = $model->where(['id'=>$info['id']])->update($data);
                    if($res === false)throw new Exception('用户数据更新失败');
                    $user_id = $info['id'];
                }
                return compact('user_id');
            }
            throw new Exception('获取openid失败');
        }catch(Exception $e){
            return $e->getMessage();
        }
    }

}