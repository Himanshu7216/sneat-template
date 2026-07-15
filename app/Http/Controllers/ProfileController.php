<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;
use Exception;


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
        if ($request->ajax()) {
            // dd($request->all());
            $validator = Validator::make($request->all(), [
                'name' => ['required', 'string', 'max:255', 'min:3', 'regex:/^[a-zA-Z\s]+$/'],

                'phone' => [
                    'required',
                    'digits:10',
                    'regex:/^[6-9]\d{9}$/',

                ],

                'dob' => [
                    'required',
                    'date',
                    'before:today',
                    'after:' . now()->subYears(120)->format('Y-m-d'),
                    'before_or_equal:' . now()->subYears(18)->format('Y-m-d'),
                ],

                'gender' => [
                    'required',
                    'in:male,female,other',
                ],

                'address' => [
                    'required',
                    'string',
                    'min:10',
                    'max:255',
                    'regex:/^[A-Za-z0-9\s,.\-\/#]+$/'
                ],

                'bio' => [
                    'nullable',
                    'string',
                    'max:500',
                    'min:10'
                ],

                'profile_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Validation failed.',
                    'errors' => $validator->errors()
                ], 422);
            }
            try {

                $user = Auth::user();
                $user->name = $request->name;
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
                $user->save();
                return response()->json([

                    "status" => "success",
                    "message" => "Profile Updated"
                ]);
            } catch (Exception $e) {
                return response()->json([
                    'status' => "error",
                    'message' => $e->getMessage(),
                ], 500);
            }
        }
        return redirect()->back();
    }
}
