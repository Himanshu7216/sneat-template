<?php

namespace App\Http\Controllers;

use App\Jobs\SendResetPasswordEmailJob;
use App\Mail\SendEmail;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;


class LoginController extends Controller
{
    public function loginPage()
    {
        return view('login');
    }
    public function login(Request $request)
    {
        if ($request->ajax()) {

            $validator = Validator::make($request->all(), [
                'email' => ['required', 'string', 'email', 'max:255'],
                'password' => ['required', 'string', 'min:8', 'max:20', 'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&]).+$/'],
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validation failed.',
                    'errors' => $validator->errors()
                ], 422);
            }
            try {
                $user = User::where('email', $request->email)->first();

                if (!empty($user) && Hash::check($request->password, $user->password)) {
                    Auth::login($user);
                    $request->session()->regenerate();
                }
                return response()->json([
                    'status' => "success",
                    'message' => 'login successful'
                ]);
            } catch (Exception $e) {
                return response()->json([
                    'status' => "error",
                    'message' => $e->getMessage(),
                ], 500);
            }
        }
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
            $validator = Validator::make(
                $request->all(),
                [
                    'username' => ['required', 'string', 'max:100', 'min:3', 'regex:/^[A-Za-z]+(?:\s[A-Za-z]+)*$/'],
                    'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
                    'phone' => [
                        'required',
                        'digits:10',
                        'max:10'
                    ],
                    'dob' => [
                        'required',
                        'date',
                        'before:today',
                        'after:' . now()->subYears(120)->format('Y-m-d'),
                    ],

                    'gender' => [
                        'required',
                        'in:male,female,other',
                    ],
                    'password' => ['required', 'string', 'min:8', 'max:20', 'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&]).+$/'],
                    'profile_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
                    'terms' => ['required', 'in:checked'],
                ]
            );
            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'errors' => $validator->errors(),
                ], 422);
            }

            try {
                $user = new User();
                $user->name = $request->username;
                $user->email = $request->email;
                $user->phone = $request->phone;
                $user->dob = $request->dob;
                $user->gender = $request->gender;
                $user->password = Hash::make($request->password);
                $user->permissions = '';

                if ($request->hasFile('profile_image')) {
                    $image = $request->file('profile_image');

                    $uuid = Str::uuid()->toString();    //random string
                    $imageName = $image->getClientOriginalName();   // name with extension
                    $filename = pathinfo($imageName, PATHINFO_FILENAME);    //name without extension
                    $extension = $image->getClientOriginalExtension();  //extension

                    $profile_image = $filename . '_' . $uuid . '.' . $extension;
                    $image->move(public_path('profile_pic'), $profile_image);
                    $user->profile_image = $profile_image;
                }

                $user->save();

                return response()->json([
                    'status' => "success",
                    'message' => 'register successful'
                ]);
            } catch (Exception $e) {
                return response()->json([
                    'status' => "error",
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
        // dd($request->all());
        $validator = Validator::make(
            $request->all(),
            [
                'email' => ['required', 'string', 'email', 'max:255']
            ]
        );
        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors(),
            ], 422);
        }
        try {
            $user = User::where('email', $request->email)->first();


            if (!$user) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Email not found.'
                ]);
            }

            $token = $token = Str::random(64);


            DB::table('password_reset_tokens')->updateOrInsert(
                ['email' => $user->email],
                [
                    'token' => Hash::make($token),
                    'created_at' => now()
                ]
            );

            $link = route('reset-password', [
                'token' => $token,
                'email' => $user->email
            ]);

            $subject = 'Reset Your Password';

            $message = [
                'name' => $user->name,
                'link' => $link,
            ];


            SendResetPasswordEmailJob::dispatch(
                $user->email,
                $subject,
                $message
            );

            return response()->json([
                'status' => 'success',
                'message' => 'Reset link sent successfully.'
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => "error",
                'message' => $e->getMessage(),
            ], 500);
        }
    }
    public function reset_password(Request $request, $token)
    {
        // dd($request->all());
        try {
            $record = DB::table('password_reset_tokens')
                ->where('email', $request->email)
                ->first();

            if (isset($record)) {
                if (Hash::check($token, $record->token)) {
                    return view('new_password', [
                        'email' => $request->email,
                        'token' => $token
                    ]);
                }else{
                    return view('token_expire_page');
                }
            }

        } catch (\Exception $e) {

            abort(404);
        }
    }
    public function new_password(Request $request)
    {
        // dd($request->all());
        $validator = Validator::make($request->all(), [
            'email' => ['required', 'string', 'email', 'max:255'],
            'new_password' => ['required', 'string', 'min:8', 'max:20', 'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&]).+$/'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed.',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $user = User::where('email', $request->email)->first();

            if (!$user || empty($user)) {

                return response()->json([
                    'status' => 'error',
                    'message' => 'User not found.'
                ]);
            }

            if (isset($user) && $request->new_password === $request->confirm_password) {
                $user->password = Hash::make($request->new_password);
                $user->save();

                DB::table('password_reset_tokens')->where('email',$request->email)->update(['token'=>'' ?? null]);
            }
            // DB::table('password_reset_tokens')->(
            //     ['email' => $user->email],
            //     [
            //         'token' => Hash::make($request->token),
            //         'created_at' => now()
            //     ]
            // );
            return response()->json([
                'status' => 'success',
                'email' => $request->email,
                'new_password' => $request->new_password,
                'message' => 'Password changed successfully.'
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => "error",
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
