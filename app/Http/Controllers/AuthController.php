<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    //View Login
    public function loginIndex(): View
    {
        return view('auth.login');
    }
    //View Register
    public function registerIndex(): View
    {
        return view('auth.register');
    }

    //Logic Register
    public function registerLogic(Request $request): View
    {
        $data = $request->all();

        $validator = Validator::make($data, [
            'name' => 'required|string',
            'email' => 'required|email:dns',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return view('auth.register')->withErrors($validator);
        }

        $user = User::where('email', $request->email)->exists();

        if ($user) {
            return view('auth.register')->withErrors(["errors" => "Your email has been registered!"]);
        }

        DB::beginTransaction();

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        DB::commit();

        return view('auth.login')->with('success', 'Registration successfull! Please login');
    }

    //Login Login
    public function loginLogic(Request $request) : RedirectResponse {

        $validator = $request->validate([
            'email' => 'required|email:dns',
            'password' => 'required|min:6'
        ]);

        if (Auth::attempt($validator)) {
           $request->session()->regenerate();
           return redirect()->intended('/');
        }

        return back()->with('loginError', 'Email or password is wrong');
    }

    public function logoutLogic() : RedirectResponse {
        Auth::logout();

        request()->session()->invalidate();

        request()->session()->regenerateToken();

        return redirect('/login');
    }
}
