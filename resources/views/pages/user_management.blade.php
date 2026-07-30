@extends('layouts.app')
@section('title', 'user-management')
@section('content')
    <div class="custom-breadcrumb-container">
        {{ Breadcrumbs::render('user_management') }}
    </div>
            {{-- @dd( auth()->user()->roles->pluck('name')->implode(', ')) --}}

    @if (session('success'))

        <div class="alert alert-success alert-dismissible fade show" role="alert">

            {{ session('success') }}

            <button type="button" class="btn-close" data-bs-dismiss="alert">
            </button>

        </div>

    @endif

    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">

                <h5 class="mb-0">
                    User Management
                </h5>

                <div>
                    @role('Super Admin')

                    <a href="{{ route('create-role') }}" class="btn btn-primary">

                        <i class="bi bi-plus-circle"></i>
                        New Role
                    </a>
                    <a href="{{ route('create-permission') }}" class="btn btn-primary">

                        <i class="bi bi-plus-circle"></i>
                        New Permission
                    </a>
                    @endrole
                    @hasanyrole(['Super Admin','Admin','Manager'])
                    <a href="{{ route('assign-permission') }}" class="btn btn-primary">

                        <i class="bi bi-plus-circle"></i>
                        Assign Permission
                    </a>

                    <a href="{{ route('assign-role') }}" class="btn btn-primary">

                        <i class="bi bi-plus-circle"></i>
                        Assign Role
                    </a>
                    <a href="{{ route('assign-permission-model') }}" class="btn btn-primary">

                        <i class="bi bi-plus-circle"></i>
                        Direct Permission
                    </a>
                    <a href="{{ route('add-user') }}" class="btn btn-primary">

                        <i class="bi bi-plus-circle"></i>
                        Add User
                    </a>
                    @endhasanyrole

                </div>


            </div>

            <div class="card-body">

                <div class="table-responsive">

                    <table class="table table-bordered table-hover align-middle">

                        <thead class="table-dark">
                            <tr>
                                <th>Sr. No.</th>
                                <th>User Name</th>
                                <th>Email</th>

                                <th>Phone</th>
                                <th>DOB</th>
                                @can('User Management')
                                <th>Role</th>
                                <th>Permissions</th>
                                <th>Action</th>
                                @endcan
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($users as $user)
                                @foreach ($user->roles as $role)


                                {{-- User 1 --}}
                                <tr>
                                    <td>{{ $loop->iteration }}</td>

                                    <td>
                                        <strong>{{ $user->name }}</strong>
                                    </td>

                                    <td>
                                        {{ $user->email }}
                                    </td>
                                <td>{{ $user->phone }}</td>
                                <td>{{ $user->dob }}</td>

@can('User Management')
<td>
                                        {{ $role->name }}
                                        {{-- @if ($user->role == "super admin")
                                            <span class="badge bg-danger">
                                                {{ $user->role }}
                                            </span>
                                        @elseif ($user->role == "admin")
                                            <span class="badge bg-warning">
                                                {{ $user->role }}
                                            </span>
                                        @else
                                            <span class="badge bg-success">
                                                {{ $user->role }}
                                            </span>
                                        @endif --}}
                                    </td>

                                    <td>
                                        @foreach($role->permissions as $permission)
                                            <li>{{$permission->name}}</li>
                                        @endforeach
                                        {{-- @if(!empty($user->permissions))
                                            @foreach($user->permissions as $permission)
                                                <span class="badge bg-success me-1">
                                                    {{ $permission }}
                                                </span>
                                            @endforeach
                                        @else
                                            <span class="text-muted">No Permissions</span>
                                        @endif --}}

                                    </td>
                                    <td>
                                        {{-- @can('Edit User') --}}
                                        @haspermission('Edit User')
                                        <a href="{{ route('edit-user', $user->id) }}" class="btn btn-sm btn-primary">

                                            <i class="bi bi-pencil"></i>
                                            Edit

                                        </a>
                                        @endhaspermission
                                        {{-- @endcan --}}
                                        @can('Delete User')

                                        <form action="{{ route('delete-user', $user->id) }}" method="POST" class="d-inline">

                                            @csrf
                                            @method('DELETE')

                                            <button type="submit" class="btn btn-sm btn-danger"
                                                onclick="return confirm('Are you sure you want to delete this user?')">

                                                <i class="bi bi-trash"></i>
                                                Delete

                                            </button>

                                        </form>
                                        @endcan
                                    </td>
                                    @endcan
                                </tr>

                                @endforeach
                            @endforeach


                        </tbody>

                    </table>

                </div>

            </div>
        </div>
    </div>
@endsection
