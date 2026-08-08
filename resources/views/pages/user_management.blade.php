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
                                        <!-- {{ $role->name }} -->
                                         @if ($role->name == "Super Admin")
                                            <span class="badge bg-dark">
                                                {{ $role->name }}
                                            </span>
                                        @elseif ($role->name == "Admin")
                                            <span class="badge bg-danger">
                                                {{ $role->name }}
                                            </span>
                                            @elseif ($role->name == "Manager")
                                            <span class="badge bg-warning">
                                                {{ $role->name }}
                                            </span>
                                        @else
                                            <span class="badge bg-success">
                                                {{ $role->name }}
                                            </span>
                                        @endif 
                                    </td>

                                    <td>
                                        @foreach($role->permissions as $permission)
                                        <ul type="circle">
                                            <li>{{$permission->name}}</li>
                                        </ul>
                                        @endforeach
                                        
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

                                        <form action="{{ route('delete-user', $user->id) }}" method="POST" class="d-inline delete-user-form">

                                            @csrf
                                            @method('DELETE')

                                            <button type="button" class="btn btn-sm btn-danger btn-delete-user" data-id="{{ $user->id }}">

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

    @push('scripts')
    <script>
        $(document).ready(function () {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            @if(session('success'))
                Swal.fire({
                    title: 'Success!',
                    text: "{{ session('success') }}",
                    icon: 'success',
                    confirmButtonText: 'OK'
                });
            @endif

            @if(session('error'))
                Swal.fire({
                    title: 'Error!',
                    text: "{{ session('error') }}",
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            @endif

            $(document).on('click', '.btn-delete-user', function (e) {
                e.preventDefault();
                let button = $(this);
                let form = button.closest('form');
                let tr = button.closest('tr');

                Swal.fire({
                    title: 'Are you sure?',
                    text: "You want to delete this user!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#696cff',
                    cancelButtonColor: '#8592a3',
                    confirmButtonText: 'Yes, delete it!',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: form.attr('action'),
                            type: 'POST',
                            data: form.serialize(),
                            dataType: 'json',
                            success: function (response) {
                                if (response.status === 'success') {
                                    Swal.fire({
                                        title: 'Deleted!',
                                        text: response.message,
                                        icon: 'success',
                                        confirmButtonText: 'OK'
                                    }).then(function () {
                                        tr.fadeOut(400, function () {
                                            $(this).remove();
                                        });
                                    });
                                } else {
                                    Swal.fire({
                                        title: 'Error!',
                                        text: response.message || 'Could not delete user.',
                                        icon: 'error'
                                    });
                                }
                            },
                            error: function (xhr) {
                                let msg = xhr.responseJSON?.message || 'Something went wrong while deleting user.';
                                Swal.fire({
                                    title: 'Error!',
                                    text: msg,
                                    icon: 'error'
                                });
                            }
                        });
                    }
                });
            });
        });
    </script>
    @endpush
@endsection
