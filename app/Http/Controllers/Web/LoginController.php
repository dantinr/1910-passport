<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LoginController extends Controller
{

    public function loginView(Request $request)
    {
        $redirect_uri = $request->get('redirect','http://local.1910.com');
        $data = [
            'redirect_uri' => $redirect_uri
        ];
        return view('web.login',$data);
    }

    public function login(Request $request)
    {
        // TODO 验证登录
        $redirect_uri = $request->input('redirect');        //跳转
        return redirect($redirect_uri);
    }
}
