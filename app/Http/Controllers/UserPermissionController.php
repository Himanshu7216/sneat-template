<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class UserPermissionController extends Controller
{
    // create role
    public function createRole()
    {
        return view('UserManagementModule.createNewRole');
    }
    public function storeRole(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255|unique:roles,name',
                'guard_name' => 'required|string|max:255',
            ]);
            // Validation failed
            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Validation failed.',
                    'errors' => $validator->errors(),
                ], 422);
            }

            // Validated data
            $validated = $validator->validated();

            Role::create($validated);

            return response()->json([
                'status' => 'success',
                'message' => 'New role created successfully.',
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Something went wrong while creating the role.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    // create permission
    public function createPermission()
    {
        return view('UserManagementModule.createNewPermission');
    }

    public function storePermission(Request $request)
    {

        try {
            $validated = $request->validate([
                'name' => [
                    'required',
                    'string',
                    'max:255',
                    'unique:permissions,name',
                ],
                'permission_type' => [
                    'required',
                    'string',
                    'max:255',
                ],
            ]);


            Permission::create($validated);

            return response()->json([
                'status' => 'success',
                'message' => 'Permission created successfully.',
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed.',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Something went wrong while creating permission.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    // assign permission to user
    public function assignPermissionForm()
    {
        // $user = User::all();
        $roles = Role::all();
        $permissions = Permission::all();
        return view('UserManagementModule.assignPermission', ['roles' => $roles, 'permissions' => $permissions]);
    }
    public function getRolePermissions($roleId)
    {
        $role = Role::findOrFail($roleId);

        $permissionIds = $role->permissions()
            ->pluck('permissions.id');

        return response()->json([
            'status' => 'success',
            'permissions' => $permissionIds
        ]);
    }

    public function assignPermissions(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'role_id' => 'required|exists:roles,id',
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed.',
                'errors' => $validator->errors(),
            ], 422);
        }

        try {

            $role = Role::findOrFail($request->role_id);

            // Agar koi permission select nahi hai
            if (empty($request->permissions)) {

                $role->syncPermissions([]);
            } else {

                $permissions = Permission::whereIn(
                    'id',
                    $request->permissions
                )->get();

                $role->syncPermissions($permissions);
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Permissions updated successfully.',
            ], 200);
        } catch (\Exception $e) {

            return response()->json([
                'status' => 'error',
                'message' => 'Unable to update permissions.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function assignRoleForm()
    {
        $user = User::all();
        $roles = Role::all();
        return view('UserManagementModule.assignRoleToUser', ['users' => $user, 'roles' => $roles]);
    }
    public function assignRole(Request $request)
    {
        try {
            $request->validate([
                'user_id' => 'required|exists:users,id',
                'role_id' => 'required|exists:roles,id',
            ]);

            $loggedInUser = Auth::user();

            $role = Role::findOrFail($request->role_id);
            $user = User::findOrFail($request->user_id);

            if ($loggedInUser->hasRole('Admin')) {

                if ($loggedInUser->id === $user->id && $role->type === 'super-admin') {
                    Log::info("You cannot assign the Super Admin role to yourself.");
                    return response()->json([
                        'status' => 'error',
                        'message' => 'You cannot assign the Super Admin role to yourself.',
                    ], 403);
                }

                if ($user->hasRole('Super Admin')) {
                Log::info("You can't change the Super Admin role.");

                    return response()->json([
                        'status' => 'error',
                        'message' => "You can't change the Super Admin role.",
                    ], 403);
                }

                if ($role->type === 'super-admin') {
                Log::info("You don't have permission to assign the Super Admin role.");

                    return response()->json([
                        'status' => 'error',
                        'message' => "You don't have permission to assign the Super Admin role.",
                    ], 403);
                }
            }
            if ($loggedInUser->hasRole('Manager')) {
                //cannot assign super admin , admin role your self
                if ($loggedInUser->id === $user->id && $role->type === 'super-admin') {
                    Log::info("You cannot assign the Super Admin role to yourself.");
                    return response()->json([
                        'status' => 'error',
                        'message' => 'You cannot assign the Super Admin role to yourself.',
                    ], 403);
                }
                if ($loggedInUser->id === $user->id && $role->type === 'admin') {
                    Log::info("You cannot assign the Admin role to yourself.");
                    return response()->json([
                        'status' => 'error',
                        'message' => 'You cannot assign the Admin role to yourself.',
                    ], 403);
                }
                // can't change super admin , admin role
                if ($user->hasRole('Super Admin')) {
                Log::info("You can't change the Super Admin role.");

                    return response()->json([
                        'status' => 'error',
                        'message' => "You can't change the Super Admin role.",
                    ], 403);
                }
                if ($user->hasRole('Admin')) {
                Log::info("You can't change the Admin role.");

                    return response()->json([
                        'status' => 'error',
                        'message' => "You can't change the Admin role.",
                    ], 403);
                }

                // can't assign super admin , admin , manager role to anyone
                if ($role->type === 'super-admin') {
                Log::info('You dont have permission to assign the Super Admin role.');

                    return response()->json([
                        'status' => 'error',
                        'message' => "You don't have permission to assign the Super Admin role.",
                    ], 403);
                }
                if ($role->type === 'admin') {
                Log::info('You dont have permission to assign the Admin role.');

                    return response()->json([
                        'status' => 'error',
                        'message' => "You don't have permission to assign the Admin role.",
                    ], 403);
                }
                if ($role->type === 'manager') {
                Log::info("you can't assign manager role directly to anyone");

                    return response()->json([
                        'status' => 'error',
                        'message' => "you can't assign manager role directly to anyone",
                    ], 403);
                }
            }
                Log::info('else part execute');
                $user->syncRoles([$role]);

                return response()->json([
                    'status' => 'success',
                    'message' => 'Role assigned successfully.',
                ]);



        } catch (ValidationException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed.',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Something went wrong while assigning Role.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
    // public function testing()
    // {
    //     $user = User::find(15);

    //     // dd('hi');

    //     //Create Roles and Permissions
    //     // $role = Role::create(['name' => 'writer']);     //10
    //     // $permission = Permission::create(['name' => 'edit articles']);  //9

    //     $role = Role::find(14);                 //10 , manager
    //     $permission = Permission::find(14);      //9

    //     // $role->givePermissionTo($adminPermission);
    //     // $role->givePermissionTo($permission);
    //     // $user->assignRole($role);
    //     //Assign A Permission To A Role
    //     // $role->givePermissionTo($permission);
    //     // $permission->assignRole($role);

    //     //Remove Permission From A Role
    //     // $role->revokePermissionTo($permission);
    //     // $permission->removeRole($role);

    //     //Sync Permissions To A Role
    //     // $role->syncPermissions($permission);       // update
    //     // $permission->syncRoles($role);

    //     // $role = Role::create(['guard_name' => 'admin', 'name' => 'manager']);
    //     // $permission = Permission::create(['guard_name' => 'admin', 'name' => 'publish articles 1']);
    //     // $permission = Permission::create(['guard_name' => 'web', 'name' => 'publish articles 2']);

    //     // $result = $user->hasPermissionTo('publish articles', 'web');
    //     // dd($user);

    //     // $permissionNames = $user->getPermissionNames();
    //     // $permissions = $user->permission;
    //     // $AssignRole=$user->assignRole('User');
    //     // dd($AssignRole);
    //     // $role = Role::create([
    //     //     'name' => 'manager',
    //     //     'guard_name' => 'web',
    //     // ]);
    //     // $adminPermission = Permission::create([
    //     //     'name' => 'publish article 1',
    //     //     'guard_name' => 'web',
    //     //     'permission_type' => 'admin',
    //     // ]);

    //     // $webPermission = Permission::create([
    //     //     'name' => 'publish article 2',
    //     //     'guard_name' => 'web',
    //     //     'permission_type' => 'web',
    //     // ]);

    //     // $user->hasPermissionTo('publish article 1');
    //     // true

    //     // $user->hasPermissionTo('publish article 2');
    //     // true
    //     // $permissions = $user->getAllPermissions()->where('permission_type','admin');    //admin guard
    //     // $permissions = $user->getAllPermissions()->where('permission_type','web');    //web guard

    //     // dd($permissions);

    //     return 'function end';
    // }


    public function assignPermissionToModelForm()
    {
        $users = User::all();
        $permissions = Permission::all();

        return view('UserManagementModule.assignPermissionToModel', [
            'users' => $users,
            'permissions' => $permissions
        ]);
    }
    public function getModelPermissions($userId)
    {
        // dd($userId);
        $permissionIds = DB::table('model_has_permissions')
            ->where('model_id', $userId)
            ->where('model_type', User::class)
            ->pluck('permission_id');
        // dd($permissionIds);

        if (!empty($permissionIds)) {
            return response()->json([
                'status' => 'success',
                'permissions' => $permissionIds
            ]);
        }

        return response()->json([
            'status' => 'success',
            'message' => "don't have permission"
        ]);
    }
    public function assignPermissionToModel(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'permissions' => 'required|array',
            'permissions.*' => 'exists:permissions,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = User::findOrFail($request->user_id);

        $permissions = Permission::whereIn(
            'id',
            $request->permissions
        )->get();

        // dd($permissions);

        $user->syncPermissions($permissions);

        return response()->json([
            'status' => 'success',
            'message' => 'Permissions assigned successfully.'
        ]);
    }
}
