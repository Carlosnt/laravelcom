<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function loginHandler(Request $request)
    {
        $filedType = filter_var($request->login_id, FILTER_VALIDATE_EMAIL) ? 'email':'username';
        if($filedType == 'email'){
            $request->validate([
            'login_id' => 'required|email|exists:admins,email',
            'password' => 'required|min:5|max:45',
            ],[
                'login_id.required' => 'E-mail ou usuário é obrigatório',
                'login_id.email' => 'E-mail inválido',
                'login_id.exists' => 'E-mail não cadastrado em nosso sistema',
                'password.required' => 'A senha é obrigatória',
            ]);
        }else{
            $request->validate([
                'login_id' => 'required|exists:admins,username',
                'password' => 'required|min:5|max:45',
            ],[
                'login_id.required' => 'E-mail ou usuário é obrigatório',
                'login_id.exists' => 'Usuário não cadastrado em nosso sistema',
                'password.required' => 'A senha é obrigatória',
            ]);
        }

        $credentials = array(
            $filedType => $request->login_id,
            'password' => $request->password
        );

        if(Auth::guard('admin')->attempt($credentials)){
            return redirect()->route('admin.home');
        }else{
            session()->flash('fail','Credenciais incorretas');
            return redirect()->route('admin.login');
        }

    }

    public function logoutHandler(Request $request)
    {
        Auth::guard('admin')->logout();       
        session()->flash('success','Você saiu, volte logo!');
        return redirect()->route('admin.login');    
    }
}
