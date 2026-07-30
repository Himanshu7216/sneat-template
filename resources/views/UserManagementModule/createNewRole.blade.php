@extends('layouts.app')

@section('title', 'Create Role')

@section('content')

    <div class="custom-breadcrumb-container">
        {{ Breadcrumbs::render('New_Role') }}
    </div>

    @include('notify::components.notify')

    <div class="container-xxl flex-grow-1 container-p-y">

        <div class="container-fluid">

            <div id="alert-box" class="mb-4"></div>

            {{-- Center Form --}}
            <div class="row justify-content-center">

                <div class="col-lg-6 col-md-8 col-sm-12">

                    <div class="card shadow-sm">

                        <div class="card-header app-header d-flex justify-content-between align-items-center">

                            <h5 class="mb-0">
                                Create New Role
                            </h5>

                            <a href="{{ url()->previous() }}" class="btn btn-light btn-sm">
                                <i class="bx bx-arrow-back"></i>
                            </a>

                        </div>

                        <div class="card-body p-4">

                            <form id="roleForm">

                                @csrf

                                {{-- Role Name --}}
                                <div class="mb-4">

                                    <label for="role_name" class="form-label">
                                        Role Name
                                    </label>

                                    <input type="text"
                                           name="name"
                                           id="role_name"
                                           class="form-control"
                                           placeholder="Enter Role Name">

                                    <small class="text-danger name_error"></small>

                                </div>


                                {{-- Guard Name --}}
                                <div class="mb-4">

                                    <label for="guard_name" class="form-label">
                                        Guard Name
                                    </label>

                                    <input type="text"
                                           name="guard_name"
                                           id="guard_name"
                                           class="form-control"
                                           value="web"
                                           placeholder="Enter Guard Name">

                                    <small class="text-danger guard_name_error"></small>

                                </div>


                                {{-- Submit Button --}}
                                <button type="submit"
                                        id="roleSubmitBtn"
                                        class="btn btn-success w-100">

                                    Create Role

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


        $('#roleForm').on('submit', function (e) {

            e.preventDefault();


            let formData = new FormData(this);


            $.ajax({

                url: '/store/role',

                type: 'POST',

                data: formData,

                processData: false,

                contentType: false,

                dataType: "json",


                success: function (response) {

                    if (response.status === "success") {

                        toastr.success(response.message);

                        $("#roleForm")[0].reset();

                        // Default guard value again
                        $("#guard_name").val("web");

                    }


                    if (response.status === "error") {

                        toastr.error(response.message);

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

                    } else {

                        toastr.error("Something went wrong.");

                        console.log(xhr.responseText);

                    }

                }

            });

        });

    });

</script>

@endpush
