<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Model\UserModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Redis;

class LoginController extends Controller
{

    /**
     * 登录页面
     * @param Request $request
     */
    public function loginView(Request $request)
    {
        if($_SERVER['uid'])
        {
            $uri = 'http://local.1910.com';     //可配置到.env
            return redirect($uri);      //已登录 跳转到主站
        }

        //未登录 显示登录页面
        $redirect_uri = $request->get('redirect','http://local.1910.com');
        $data = [
            'redirect_uri' => $redirect_uri
        ];
        return view('web.login',$data);
    }

    /**
     * 登录
     * @param Request $request
     */
    public function login(Request $request)
    {
        $redirect_uri = $request->input('redirect');        //跳转

        $info = $request->post('user_name');       //用户可输入 用户名 或 Email 或 手机号
        $pass = $request->post('user_pass');
        //用户名 Email 登录
        $u = UserModel::where(['email'=>$info])->orWhere(['user_name'=>$info])->first();
        //用户不存在
        if(empty($u))
        {
            $data = [
                'redirect'  => '/web/login?redirect_uri='.$redirect_uri,
                'msg'       => "用户名或密码不正确，请重新登录"
            ];
            return view('web.302',$data);
        }
        //验证密码
        if( password_verify($pass,$u->password) )
        {
            //执行登录
            $token = UserModel::webLogin($u->user_id,$u->user_name);
            Cookie::queue('token',$token,60*24*30,'/','1910.com',false,true);      //120分钟
            $data = [
                'redirect'  => $redirect_uri,
                'msg'       => "登录成功，正在跳转"
            ];

            return view('web.302',$data);
        }else{
            $data = [
                'redirect'  => '/web/login?redirect_uri='.$redirect_uri,
                'msg'       => "用户名或密码不正确，请重新登录"
            ];
            return view('web.302',$data);
        }

        return redirect($redirect_uri);
    }

    /**
     * 退出
     *  清redis
     */
    public function logout(Request $request)
    {
        $redirect_uri = $request->get('redirect',env('SHOP_DOMAIN'));
        $token_key = 'h:login_info:'.$_SERVER['token'];
        Redis::del($token_key);
        return redirect($redirect_uri);
    }
}
