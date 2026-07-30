@extends('layouts.app')
@section('title', 'assign role to user')
@section('content')
    <div class="custom-breadcrumb-container">
        {{ Breadcrumbs::render('Assign_Role') }}
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
                                Assign Role to User
                            </h5>

                            <a href="{{ url()->previous() }}" class="btn btn-light btn-sm">
                                <i class="bx bx-arrow-back"></i>
                            </a>

                        </div>
                        <div class="card-body p-4">
                            <form id="assignRole" action="/assignRole" method=""> @csrf <div class="mb-4">
                                    <label class="form-label"> Select User </label> <select name="user_id"
                                        class="form-select">
                                        <option value=""> Select User </option> @foreach ($users as $user) <option
                                            value="{{ $user->id }}"> {{ $user->name }} ({{ $user->email }}) </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mb-4"> <label class="form-label"> Select Role </label> <select name="role_id"
                                        class="form-select">
                                        <option value=""> Select Role </option> @foreach ($roles as $role) <option
                                        value="{{ $role->id }}"> {{ $role->name }} </option> @endforeach
                                    </select> </div> <button type="submit" class="btn btn-success w-100"> Assign Role to
                                    User
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


            $('#assignRole').on(
                'submit',
                function (e) {
                    e.preventDefault();
                    let formData = new FormData(this);
                    console.log(Object.fromEntries(formData.entries()));
                    $.ajax({
                        url: '/assignRole',
                        type: 'POST',
                        data: formData,
                        processData: false,
                        contentType: false,
                        dataType: 'json',
                        success: function (response) {
                            if ((response.status === "success")) {
                                toastr.success(response.message);
                                $("#assignRole")[0].reset();
                                $(".text-success").text("");
                            }
                            if ((response.status === "error")) {
                                toastr.error(response.message);
                                $(".text-danger").text("");
                            }
                        },

                        error: function (xhr) {

                            $(".text-danger").text("");

                            if (xhr.status === 422) {
                                let errors = xhr.responseJSON.errors;

                                $.each(errors, function (key, value) {
                                    $("." + key + "_error").text(value[0]);
                                    toastr.error(value[0]);
                                });

                            } else if (xhr.status === 403) {

                                toastr.error(xhr.responseJSON.message);

                            } else {

                                toastr.error("Something went wrong.");
                                console.log(xhr.responseText);
                            }
                        }

                    });

                }

            );

        });

    </script>

@endpush
