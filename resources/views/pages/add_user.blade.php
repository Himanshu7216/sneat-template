@extends('layouts.app')
@section('title', 'add')
@section('content')
    <div class="custom-breadcrumb-container">
        {{ Breadcrumbs::render('Add') }}
    </div>

    <div class="container-xxl flex-grow-1 container-p-y">
<div class="container">

    <div class="row justify-content-center">

        <div class="col-md-8">

            <div class="card">

                {{-- <div class="card-header">
                    <h5 class="mb-0">
                        Add New User
                    </h5>
                </div> --}}
                <div class="card-header app-header d-flex justify-content-between align-items-center">

                            <h5 class="mb-0">
                                Add New User
                            </h5>

                            <a href="{{ url()->previous() }}" class="btn btn-light btn-sm">
                                <i class="bx bx-arrow-back"></i>
                            </a>

                        </div>

                <div class="card-body">

                    <form action="{{ route('store-user') }}"
                          method="POST">

                        @csrf

                        {{-- Name --}}
                        <div class="mb-3">

                            <label class="form-label">
                                Name
                            </label>

                            <input type="text"
                                   name="name"
                                   id="name"
                                   class="form-control @error('name') is-invalid @enderror"
                                   value="{{ old('name') }}"
                                   placeholder="Enter user name">

                            @error('name')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>


                        {{-- Email --}}
                        <div class="mb-3">

                            <label class="form-label">
                                Email
                            </label>

                            <input type="email"
                                   name="email"
                                   id="email"
                                   class="form-control @error('email') is-invalid @enderror"
                                   value="{{ old('email') }}"
                                   placeholder="Enter email">

                            @error('email')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>

                        {{-- Password --}}
                        <div class="mb-3">

                            <label class="form-label">
                                Password
                            </label>

                            <input
                                type="password"
                                name="password"
                                id="password"
                                class="form-control @error('password') is-invalid @enderror"
                                placeholder="Enter password">

                            @error('password')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>

{{-- Confirm Password --}}
{{-- <div class="mb-3">

    <label class="form-label">
        Confirm Password
    </label>

    <input
        type="password"
        name="password_confirmation"
        class="form-control"
        placeholder="Confirm password">

</div> --}}

                        {{-- Role --}}
                        <div class="mb-3">

                            <label class="form-label">
                                Role
                            </label>

                            <select name="role"
                                    class="form-select @error('role') is-invalid @enderror">

                                <option value="">
                                    Select Role
                                </option>

                                <option value="user"
                                    {{ old('role') == 'user' ? 'selected' : '' }}>
                                    User
                                </option>

                                <option value="admin"
                                    {{ old('role') == 'admin' ? 'selected' : '' }}>
                                    Admin
                                </option>

                                <option value="super admin"
                                    {{ old('role') == 'super admin' ? 'selected' : '' }}>
                                    Super Admin
                                </option>

                            </select>

                            @error('role')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>


                        <div class="mb-3">
                            <label class="form-label">Permissions</label>

                            {{-- @foreach($availablePermissions as $permission)
                                <div class="form-check">
                                    <input
                                    class="form-check-input"
                                    type="checkbox"
                                    name="permissions[]"
                                    value="{{ $permission->name }}"
                                    id="permission_{{ Str::slug($permission->name) }}"
                                    {{ in_array($permission->name, old('permissions', [])) ? 'checked' : '' }}
                                >

                                <label class="form-check-label" for="permission_{{ Str::slug($permission->name) }}">
                                    {{ $permission->name }}
                                </label>
                                </div>
                            @endforeach

                            @error('permissions')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror --}}
                        </div>


                        {{-- Buttons --}}
                        <div class="d-flex gap-2">

                            <button type="submit"
                                    class="btn btn-primary">

                                <i class="bi bi-person-plus"></i>
                                Add User

                            </button>

                            <a href="{{ route('user-management') }}"
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
