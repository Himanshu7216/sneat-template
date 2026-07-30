<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Continent;
use App\Models\Country;
use App\Models\City;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class AdminController extends Controller
{
    public function dashboard()
    {
        return view('pages.dashboard');
    }
    public function analytics()
    {
        return view('pages.analytics');
    }
    public function user_management()
    {
        $user = User::with('roles.permissions')->get();
        // $users = User::with(['roles.permissions', 'permissions'])->get();


        return view('pages.user_management', ['users' => $user]);
    }
    public function editUserManagement($id)
    {
        $user = User::findOrFail($id);

        return view(
            'pages.user_permission_edit_form',
            ['user' => $user]
        );
    }

    public function updateUserManagement(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validatedData = $request->validate([

            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')
                    ->ignore($user->id),
            ],

            'phone' => [
                'nullable',
                'string',
                'max:15',
            ],




        ]);

        $user->update([
            'name'=>$request->name,
            'email'=>$request->email,
            'phone'=>$request->phone,
            // 'role'=>$request->role,
            // 'permissions'=>$request->permissions
        ]);

        return redirect()
            ->route('user-management')
            ->with('success', 'User updated successfully.');
    }
    public function deleteUserManagement($id)
    {
        $user = User::findOrFail($id);

        $user->delete();

        return redirect()
            ->route('user-management')
            ->with('success', 'User deleted successfully.');
    }

    public function createUser()
    {
        // dd($permission);
        return view('pages.add_user');
    }
    //remove from
    public function add_role_and_permission_form()
    {
        $user = User::all();
        // dd($user);
        $roles = Role::all();
        $permissions = Permission::all();
        return view('pages.add_role_and_permission_form',['users'=>$user,'roles'=>$roles, 'permissions'=>$permissions]);
    }

    public function storeUser(Request $request)
    {
        $validatedData = $request->validate([

            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'email' => [
                'required',
                'email',
                'max:255',
                'unique:users,email',
            ],

            'phone' => [
                'nullable',
                'string',
                'max:15',
            ],

            'password' => [
                'required',
                'string',
                'min:8',
            ],


        ]);

        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        // $user->role = $request->role;
        // $user->permissions = $request->permissions;

        if ($user->save()) {
            return redirect()
                ->route('user-management')
                ->with('success', 'User added successfully.');
        }
    }

    public function storeModule(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:191',
            'perm_key' => 'required|string|max:191|unique:module_permissions,perm_key',
            'type' => 'required|in:module,action',
        ]);

        // ModulePermission::create([
        //     'name'     => $request->name,
        //     'perm_key' => $request->perm_key,
        //     'type'     => $request->type,
        // ]);
        return redirect()->back();
    }





    public function home()
    {
        return view('tempView.home');
    }
    public function continent($name)
    {
        // dd($name);
        // $name="Korea";
        $continent = Continent::where('name', $name)->first();
        return view('tempView.continent', ['continent' => $continent]);
    }
    public function country($name)
    {
        // dd($name);
        // $name="South Korea";
        $country = Country::where('name', $name)->first();
        $continent = $country->continent;
        // dd($country);

        return view('tempView.country', ['country' => $country, 'continent' => $continent]);
    }
    public function city($name)
    {
        // $name="Seoul";
        $city = City::where('name', $name)->first();
        $country = $city->country;
        $continent = $country->continent;

        return view('tempView.city', ['continent' => $continent, 'country' => $country, 'city' => $city]);
    }
}


















    // public function home(){
    //     $continent= "Korea";
    //     return view('tempView.home',['continent'=>$continent]);
    // }

    //  public function continent($name){
    //        $continent = Continent::where('name', $name)->first();
    //     return view('tempView.continent', compact('continent'));
    // }

    //  public function country($name = 'South Africa'){
    //        $country = Country::where('name', $name)->first();
    //        $continent = $country->continent;

    //     return view('tempView.country', compact('continent','country'));
    // }

    //  public function city($name ='Johannesburg'){
    //        $city = City::where('name', $name)->first();
    //     $country = $city->country;

    // $continent = $country->continent;

    // return view('tempView.city', compact(
    //     'continent',
    //     'country',
    //     'city'
    // ));
//     }

//     public function dashboard(){
//         return view('pages.dashboard');
//     }
//     public function analytics(){
//         return view('pages.analytics');
//     }
// }
