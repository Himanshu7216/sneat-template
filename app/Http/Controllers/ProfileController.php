<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

class ProfileController extends Controller
{
    public function profile()
    {
        $user_id = Auth::id();

        $user = User::where('id', $user_id)->first();
        return view('profile.userProfile', ['user' => $user]);
    }
    public function profile_Update(Request $request)
    {
        // dd($request->all());
       $validator = Validator::make($request->all(), [
            'name' => ['required','string','max:255'],

            'email' => ['required','email','max:255',Rule::unique('users', 'email')->ignore(Auth::id())],

            'phone' => ['nullable','digits_between:10,15'],

            'dob' => ['nullable','date','before:today'],

            'gender' => ['nullable',Rule::in(['Male', 'Female', 'Other'])],

            'address' => ['nullable','string','max:500'],

            'bio' => ['nullable','string','max:1000'],

            'profile_image' => ['nullable','image','mimes:jpg,jpeg,png,webp','max:2048'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed.',
                'errors' => $validator->errors()
            ], 422);
        }
        $user = Auth::user();
        $user->name= $request->name;
        $user->email = $request->email;
        $user->phone = $request->phone;
        $user->dob = $request->dob;
        $user->gender = $request->gender;
        $user->address = $request->address;
        $user->bio = $request->bio;

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

        if($user->save()){
            return redirect()->route('dashboard');
        }
        return redirect()->back();
    }
}
