<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    function loginView()
    {
        return view("auth.login");
    }

    public function login(LoginRequest $request)
    {
        if (\Auth::attempt($request->only(["email", "password"]))) {
            return response()->json(["status" => true, "redirect_location" => url("dashboard")]);
        } else {
            return response()->json([["Invalid credentials"]], 422);

        }
    }

    function registerView()
    {
        return view("auth.register");
    }

    function register(RegisterRequest $request)
    {

        //validations are passed, save new user in database
        $User = new User();
        $User->name = $request->name;
        $User->email = $request->email;
        $User->password = bcrypt($request->password);
        $User->save();
        return redirect()->route('dashboard');
    }

    function logout()
    {
        \Auth::logout();
        return redirect()->route('login');
    }
}
