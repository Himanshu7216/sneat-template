<?php

namespace App\Http\Controllers;

use App\Models\User;
use Exception;
use Illuminate\Http\Client\Request as ClientRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{
    public function loginPage()
    {
        return view('login');
    }
    public function login(Request $request){

        $validated = $request->validate([
                'email' => ['required', 'string', 'email', 'max:255'],
                'password' => [
                    'required',
                    'string',
                    'min:8',
                    'max:20',
                    'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&]).+$/'
                ]
            ]);
        $user = User::where('email',$request->email)->first();
        if(!empty($user) && Hash::check($request->password,$user->password)){
            Auth::login($user);
            // $request->session()->regenerate();
            return view('dashboard');
        }

    }
    public function logout(Request $request){
        Auth::logout();

        return redirect('/');
    }
    public function registerPage()
    {
        return view('register');
    }
    public function register(Request $request)
    {
        // dd($request->all());
        if ($request->ajax()) {
             Validator::make($request->all(),
                [
                    'username' => ['required', 'string', 'max:100', 'min:3', 'regex:/^[a-zA-Z\s]+$/'],
                    'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
                    'password' => ['required', 'string', 'min:8', 'max:20', 'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&]).+$/'],
                ]
            );
            try {
                $user = new User();
                $user->name = $request->username;
                $user->email = $request->email;
                $user->password = $request->password;
                $user->save();

                return response()->json([
                    'status' => 'success',
                    'message' => 'Signup successful'
                ]);
            } catch (Exception $e) {

                return response()->json([
                    'status' => 'error',
                    'message' => $e->getMessage(),
                ], 500);
            }
        }

        return view('login');
    }
}
