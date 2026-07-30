<?php

use Diglactic\Breadcrumbs\Breadcrumbs;
use Diglactic\Breadcrumbs\Generator as BreadcrumbTrail;

//for demo
Breadcrumbs::for('home', function (BreadcrumbTrail $trail) {
    $trail->push('Home', route('home'));
});
Breadcrumbs::for('continent', function (BreadcrumbTrail $trail,$continent) {
    $trail->parent('home');
    $trail->push($continent->name, route('continent',$continent->name));
});
Breadcrumbs::for('country', function (BreadcrumbTrail $trail,$continent,$country) {
    // dd($country->name);
    $trail->parent('continent',$continent);
    $trail->push($country->name, route('country',$country->name));
});
Breadcrumbs::for('city',function (BreadcrumbTrail $trail,$continent,$country,$city){
    $trail->parent('country',$continent,$country);
    $trail->push($city->name,route('city',$city->name));
});


Breadcrumbs::for('dashboard',function(BreadcrumbTrail $trail){
    $trail->push('Dashboard',route('dashboard'));
});
Breadcrumbs::for('profile',function(BreadcrumbTrail $trail){
    $trail->parent('dashboard');
    $trail->push('Profile',route('show-profile'));
});
Breadcrumbs::for('analytics',function(BreadcrumbTrail $trail){
    $trail->parent('dashboard');
    $trail->push('Analytics',route('analytics'));
});

Breadcrumbs::for('user_management',function(BreadcrumbTrail $trail){
    $trail->parent('dashboard');
    $trail->push('User Management',route('user-management'));
});
Breadcrumbs::for('Edit',function(BreadcrumbTrail $trail,$id){
    $trail->parent('user_management');
    $trail->push('Edit',route('edit-user',$id));
});
Breadcrumbs::for('Add',function(BreadcrumbTrail $trail){
    $trail->parent('user_management');
    $trail->push('Add',route('add-user'));
});
Breadcrumbs::for('Add_Module',function(BreadcrumbTrail $trail){
    $trail->parent('user_management');
    $trail->push('Add Module',route('add-module'));
});

Breadcrumbs::for('New_Role',function(BreadcrumbTrail $trail){
    $trail->parent('user_management');
    $trail->push('New Role',route('create-role'));
});

Breadcrumbs::for('New_permission',function(BreadcrumbTrail $trail){
    $trail->parent('user_management');
    $trail->push('New Permission',route('create-permission'));
});
Breadcrumbs::for('Assign_Permission',function(BreadcrumbTrail $trail){
    $trail->parent('user_management');
    $trail->push('Assign Permission',route('assign-permission'));
});
Breadcrumbs::for('Assign_Role',function(BreadcrumbTrail $trail){
    $trail->parent('user_management');
    $trail->push('Assign Role',route('assign-role'));
});
Breadcrumbs::for('Direct_Permission',function(BreadcrumbTrail $trail){
    $trail->parent('user_management');
    $trail->push('Direct Permission',route('assign-permission-model'));
});



