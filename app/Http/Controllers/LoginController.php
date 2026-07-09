<?php

namespace App\Http\Controllers;

use App\Mail\SendEmail;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Mail;

class LoginController extends Controller
{
    public function loginPage()
    {
        return view('login');
    }
    public function login(Request $request)
    {
            $validator = Validator::make($request->all(), [
            'email' => ['required', 'string', 'email', 'max:255'],
            'password' => [
                'required',
                'string',
                'min:8',
                'max:20',

            ]
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed.',
                'errors' => $validator->errors()
            ], 422);
        }
        $user = User::where('email', $request->email)->first();
        // dd(Hash::check($request->password, $user->password));
        // dd('hi');
        if (!empty($user) && Hash::check($request->password, $user->password)) {
            Auth::login($user);
            $request->session()->regenerate();
            }
        return redirect()->route('dashboard',['user'=>$user]);
    }
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerate();
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
            Validator::make(
                $request->all(),
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

    public function forgot_password()
    {
        // dd('hi');
        return view('forgot_password');
    }

    public function send_reset_link(Request $request)
{

    $request->validate([
        'email' => 'required|email'
    ]);

    $user = User::where('email', $request->email)->first();

    if (!$user) {
        return response()->json([
            'status' => 'error',
            'message' => 'Email not found.'
        ]);
    }

    $token = Crypt::encryptString($user->email);

    $link = route('reset-password', $token);

    $subject = 'Reset Your Password';
    // dd('hiiiiii');
// dd($link,$subject);
    $message = [
        'name' => $user->name,
        'link' => $link,
    ];

    Mail::to($user->email)->send(new SendEmail($subject, $message));

    return redirect('/')->with([
        'status' => 'success',
        'message' => 'Reset link sent successfully.'
    ]);
    // return response()->json([
    //     'status' => 'success',
    //     'message' => 'Reset link sent successfully.'
    // ]);
}
    public function reset_password($token)
    {

        try {

            $email = Crypt::decryptString($token);

            return view('new_password', ['email'=>$email]);
        } catch (\Exception $e) {

            abort(404);
        }
    }
    public function new_password(Request $request)
    {
        // dd($request->all());
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'new_password' => 'required|min:8',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed.',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = User::where('email', $request->email)->first();

        if (!$user || empty($user)) {

            return response()->json([
                'status' => 'error',
                'message' => 'User not found.'
            ]);
        }

        if(isset($user) && $request->new_password === $request->confirm_password){
            $user->password = Hash::make($request->new_password);
            $user->save();
        }
        return response()->json([
            'status' => 'success',
            'email'=>$request->email,
            'new_password' => $request->new_password,
            'message' => 'Password changed successfully.'
        ]);

    }
}
