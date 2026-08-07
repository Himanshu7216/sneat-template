<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserPermissionController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get('/', function () {
        return view('welcome');
    });
    Route::get('/login', [LoginController::class, 'loginPage'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);

    Route::get('/register', [LoginController::class, 'registerPage'])->name('register');
    Route::post('/register', [LoginController::class, 'register']);

    Route::get('/forgot_password', [LoginController::class, 'forgot_password'])->name('forgot-password-page');
    Route::post('/forgot_password', [LoginController::class, 'send_reset_link'])
        ->name('forgot-password');

    // open when reset token send on mail
    Route::get('/reset-password/{token}', [LoginController::class, 'reset_password'])->name('reset-password');
    Route::post('/new-password', [LoginController::class, 'new_password'])->name('new-password');
});







Route::middleware('auth')->group(function () {
    Route::get('/logout', [LoginController::class, 'logout'])->name('logout');

    // for demo Breadcrumbs navigation Begins
    // Dynamic
    Route::get('/home', [AdminController::class, 'home'])->name('home');
    Route::get('/continent/{name}', [AdminController::class, 'continent'])->name('continent');
    Route::get('/country/{name}', [AdminController::class, 'country'])->name('country');
    Route::get('/city/{name}', [AdminController::class, 'city'])->name('city');

    //static
    // Route::get('/continent',[AdminController::class,'continent'])->name('continent');
    // Route::get('/country',[AdminController::class,'country'])->name('country');
    // Route::get('/city',[AdminController::class,'city'])->name('city');
    // ends
    // Route::get('/dashboard/{name}',[AdminController::class,'dashboard'])->name('dashboard');

    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/analytics', [AdminController::class, 'analytics'])->name('analytics');
    Route::get('/profile', [ProfileController::class, 'profile'])->name('show-profile');

    Route::put('/profile_update', [ProfileController::class, 'profile_Update'])->name('profile.updateprofile.update');

    Route::get('/user_management', [AdminController::class, 'user_management'])->name('user-management');
    Route::get('/user_management/{id}/edit', [AdminController::class, 'editUserManagement'])->name('edit-user');
    Route::put('/user_management/{id}', [AdminController::class, 'updateUserManagement'])->name('update-user');
    Route::delete('/user_management/{id}', [AdminController::class, 'deleteUserManagement'])->name('delete-user');

    Route::get('/user_management/create', [AdminController::class, 'createUser'])->name('add-user');


    Route::post('/user_management/storeUser', [AdminController::class, 'storeUser'])->name('store-user');

    Route::post('/user_management/storeModule', [AdminController::class, 'storeModule'])->name('store-module');

    //testing role and permission methods
    Route::get('/testing', [UserPermissionController::class, 'testing']);

    //for super admin access
    Route::middleware('role:Super Admin')->group(function () {
        Route::get('/create/role', [UserPermissionController::class, 'createRole'])->name('create-role');
        Route::post('/store/role', [UserPermissionController::class, 'storeRole']);
        Route::get('/create/permission', [UserPermissionController::class, 'createPermission'])->name('create-permission');
        Route::post('/store/permission', [UserPermissionController::class, 'storePermission']);
    });

    //for super admin, admin, manager access
    Route::middleware(['role:Super Admin|Admin|Manager'])->group(function () {

        Route::get('/assignPermission', [UserPermissionController::class, 'assignPermissionForm'])->name('assign-permission');
        Route::get('/roles/{role}/permissions', [UserPermissionController::class, 'getRolePermissions'])->name('roles.permissions');
        Route::post('/assignPermissionToRole', [UserPermissionController::class, 'assignPermissions']);

        Route::get('/assignRole', [UserPermissionController::class, 'assignRoleForm'])->name('assign-role');
        Route::post('/assignRole', [UserPermissionController::class, 'assignRole']);


        Route::get('/assignPermissionToModel', [UserPermissionController::class, 'assignPermissionToModelForm'])
            ->name('assign-permission-model');

        Route::get('/users/{user}/permissions', [UserPermissionController::class, 'getModelPermissions'])
            ->name('users.permissions');

        Route::post('/assignPermissionToModel', [UserPermissionController::class, 'assignPermissionToModel']);
    });


    //category
    Route::get('/category', [ProductController::class, 'getCategory'])->name('show-category');

    Route::get('/category/create', [ProductController::class, 'createCategory'])->name('create-category');
    Route::post('/category/store', [ProductController::class, 'storeCategory']);

    Route::get('/category/{id}/edit', [ProductController::class, 'editCategory'])
        ->name('edit-category');

    Route::post('/category/{id}/update', [ProductController::class, 'updateCategory'])
        ->name('update-category');

    Route::delete('/category/{id}', [ProductController::class, 'deleteCategory'])
        ->name('delete-category');



    //products
    Route::get('/products', [ProductController::class, 'getproducts'])->name('show-products');

    Route::get('/products/create', [ProductController::class, 'createProduct'])->name('create-product');
    Route::post('/products/store', [ProductController::class, 'storeProduct']);

    Route::get('/products/{id}/edit', [ProductController::class, 'editProduct'])
        ->name('edit-products');

     Route::post('/products/{id}/update', [ProductController::class, 'updateProduct'])
        ->name('update-products');

    Route::delete('/products/{id}', [ProductController::class, 'deleteProduct'])
        ->name('delete-product');


    Route::view('/dropzone','product_management.dropzone_image')->name('dropzone');
    Route::post('/upload-image',[ProductController::class,'uploadImage'])
        ->name('products.uploadImage');
});
