@extends('layouts.app')
@section('title', 'assign role to user')
@section('content')
    <div class="custom-breadcrumb-container">
        {{ Breadcrumbs::render('Direct_Permission') }}
    </div>
    @include('notify::components.notify')

    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="container-fluid">
            <div id="alert-box" class="mb-4"></div>
            <div class="row justify-content-center">

                <div class="col-lg-6 col-md-8 col-sm-12">

                    <div class="card  shadow-sm">
                        <div class="card-header app-header d-flex justify-content-between align-items-center">

                            <h5 class="mb-0">
                                Assign Permission To User
                            </h5>

                            <a href="{{ url()->previous() }}" class="btn btn-light btn-sm">
                                <i class="bx bx-arrow-back"></i>
                            </a>

                        </div>
                        <div class="card-body p-4">
                            <form id="directPermission" action="" method=""> @csrf
                                <div class="mb-4">

                                    <label class="form-label">
                                        Select User
                                    </label>

                                    <select name="user_id" id="assign_user_id" class="form-select">

                                        <option value="">
                                            Select User
                                        </option>

                                        @foreach($users as $user)

                                            <option value="{{ $user->id }}">

                                                {{ $user->name }}

                                            </option>

                                        @endforeach

                                    </select>

                                    <small class="text-danger user_id_error"></small>

                                </div>
                                <div class="mb-4">

                                    <label class="form-label">
                                        Select Permissions
                                    </label>

                                    <div class="permission-list">

                                        @foreach($permissions as $permission)

                                            <div class="form-check mb-2">

                                                <input type="checkbox" class="form-check-input permission-checkbox"
                                                    id="permission_{{ $permission->id }}" value="{{ $permission->id }}"
                                                    name="permissions[]">

                                                <label class="form-check-label" for="permission_{{ $permission->id }}">

                                                    {{ $permission->name }}

                                                </label>

                                            </div>

                                        @endforeach

                                    </div>

                                    <small class="text-danger permissions_error"></small>

                                </div>
                                <button type="submit" class="btn btn-success w-100" id="assignModelPermissionBtn">

                                    Assign Permission To User

                                </button>
                            </form>
                        </div>
                    </div>


                </div>

            </div>

        </div>
    </div>
@endsection


@push('scripts')


    <script>

        $(document).ready(function () {
            $.ajaxSetup({
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
                },
            });
            toastr.options.preventDuplicates = true;

            $('#assign_user_id').on('change', function () {

                let userId = $(this).val();

                $('.permission-checkbox').prop('checked', false);

                if (!userId)
                    return;

                $.ajax({

                    url: '/users/' + userId + '/permissions',

                    type: 'GET',

                    success: function (response) {

                        if (response.status == "success") {

                            $.each(response.permissions, function (index, permissionId) {

                                $('#permission_' + permissionId)
                                    .prop('checked', true);

                            });

                        }

                    },

                    error: function () {

                        toastr.error('Unable to load permissions.');

                    }

                });

            });

            $('#directPermission').submit(function (e) {

                e.preventDefault();

                let formData = new FormData(this);

                $.ajax({

                    url: '/assignPermissionToModel',

                    type: 'POST',

                    data: formData,

                    processData: false,

                    contentType: false,

                    success: function (response) {

                        // toastr.success(response.message);
                        if ((response.status === "success")) {
                                toastr.success(response.message);
                                $("#directPermission")[0].reset();
                                $(".text-success").text("");
                            }
                            if ((response.status === "error")) {
                                toastr.error(response.message);
                                $(".text-danger").text("");
                            }

                    },

                    error: function (xhr) {

                        $(".text-danger").text("");

                        if (xhr.status == 422) {

                            let errors = xhr.responseJSON.errors;

                            $.each(errors, function (key, value) {

                                $("." + key + "_error").text(value[0]);

                                toastr.error(value[0]);

                            });

                        }
                        else {

                            toastr.error("Something went wrong.");

                        }

                    }

                });

            });

        });

    </script>

@endpush
