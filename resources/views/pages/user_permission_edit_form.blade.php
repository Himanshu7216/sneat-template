@extends('layouts.app')
@section('title', 'edit')
@section('content')

@php
    $selectedPermissions = old('permissions', $user->permissions ?? []);
@endphp


    <div class="custom-breadcrumb-container">
        {{ Breadcrumbs::render('Edit',$user->id) }}
    </div>

    <div class="container-xxl flex-grow-1 container-p-y">
<div class="container">

    <div class="row justify-content-center">

        <div class="col-md-8">

            <div class="card">

                <div class="card-header">
                    <h5 class="mb-0">
                        Edit User
                    </h5>
                </div>

                <div class="card-body">

                    <form action="{{ route('update-user', $user->id) }}"
                          method="POST">

                        @csrf
                        @method('PUT')

                        {{-- Name --}}
                        <div class="mb-3">

                            <label class="form-label">
                                Name
                            </label>

                            <input type="text"
                                   name="name"
                                   class="form-control"
                                   value="{{ old('name', $user->name) }}">

                        </div>


                        {{-- Email --}}
                        <div class="mb-3">

                            <label class="form-label">
                                Email
                            </label>

                            <input type="email"
                                   name="email"
                                   class="form-control"
                                   value="{{ old('email', $user->email) }}">

                        </div>


                        {{-- Phone --}}
                        <div class="mb-3">

                            <label class="form-label">
                                Phone
                            </label>

                            <input type="text"
                                   name="phone"
                                   class="form-control"
                                   value="{{ old('phone', $user->phone) }}">

                        </div>


                        {{-- Role --}}
                        <div class="mb-3">

                            <label class="form-label">
                                Role
                            </label>

                            <select name="role"
                                    class="form-select">

                                <option value="user"
                                    {{ $user->role == 'user' ? 'selected' : '' }}>
                                    User
                                </option>

                                <option value="admin"
                                    {{ $user->role == 'admin' ? 'selected' : '' }}>
                                    Admin
                                </option>

                                <option value="super admin"
                                    {{ $user->role == 'super admin' ? 'selected' : '' }}>
                                    Super Admin
                                </option>

                            </select>

                        </div>


                        {{-- Permissions --}}
                        <div class="mb-3">
    <label class="form-label">Permissions</label>

    {{-- @php
        $selectedPermissions = old('permissions', $user->permissions ?? []);
    @endphp --}}

    {{-- @foreach($availablePermissions as $permission)
        <div class="form-check">

            <input
                class="form-check-input"
                type="checkbox"
                name="permissions[]"
                value="{{ $permission->name }}"
                id="permission_{{ Str::slug($permission->name) }}"
                {{ in_array($permission->name, $selectedPermissions) ? 'checked' : '' }}
            >

            <label
                class="form-check-label"
                for="permission_{{ Str::slug($permission->name) }}">
                {{ $permission->name }}
            </label>

        </div>
    @endforeach

    @error('permissions')
        <small class="text-danger">{{ $message }}</small>
    @enderror --}}
</div>


                        <div class="d-flex gap-2">

                            <button type="submit"
                                    class="btn btn-primary">

                                Update User

                            </button>

                            <a href="{{ url('/user_management') }}"
                               class="btn btn-secondary">

                                Cancel

                            </a>

                        </div>

                    </form>

                </div>

            </div>

        </div>

    </div>

</div>
</div>
@endsection
