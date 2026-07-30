@extends('layouts.app')

@section('title', 'Add Role & Permission')

@section('content')

    <div class="custom-breadcrumb-container">
        {{ Breadcrumbs::render('New_permission') }}
    </div>

    @include('notify::components.notify')

    <div class="container-xxl flex-grow-1 container-p-y">

        <div class="container-fluid">

            <div id="alert-box" class="mb-4"></div>

            <div class="row justify-content-center">

                {{-- ================= CREATE PERMISSION ================= --}}

                <div class="col-lg-6 col-md-8 col-sm-12">

                    <div class="card shadow-sm">


                        <div class="card-header app-header d-flex justify-content-between align-items-center">

                            <h5 class="mb-0">
                                Create New Permission
                            </h5>

                            <a href="{{ url()->previous() }}" class="btn btn-light btn-sm">
                                <i class="bx bx-arrow-back"></i>
                            </a>

                        </div>


                        <div class="card-body p-4">

                            <form id="permissionForm">

                                @csrf

                                <input type="hidden"
                                       name="permission_id"
                                       id="permission_id">


                                {{-- Permission Name --}}

                                <div class="mb-4">

                                    <label for="permission_name"
                                           class="form-label">

                                        Permission Name

                                    </label>


                                    <input type="text"
                                           name="name"
                                           id="permission_name"
                                           class="form-control"
                                           placeholder="Enter Permission Name">


                                    <small class="text-danger name_error"></small>

                                </div>


                                {{-- Permission Type --}}

                                <div class="mb-4">

                                    <label for="permission_type"
                                           class="form-label">

                                        Permission Type

                                    </label>


                                    <input type="text"
                                           name="permission_type"
                                           id="permission_type"
                                           class="form-control"
                                           placeholder="Enter Permission Type">


                                    <small class="text-danger permission_type_error"></small>

                                </div>


                                {{-- Submit Button --}}

                                <button type="submit"
                                        id="permissionSubmitBtn"
                                        class="btn btn-success w-100">

                                    Create Permission

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

                "X-CSRF-TOKEN":
                    $('meta[name="csrf-token"]').attr("content"),

            },

        });


        toastr.options.preventDuplicates = true;


        $('#permissionForm').on('submit', function (e) {

            e.preventDefault();


            let formData = new FormData(this);


            $.ajax({

                url: "/store/permission",

                type: "POST",

                data: formData,

                processData: false,

                contentType: false,

                dataType: "json",


                success: function (response) {


                    if (response.status === "success") {

                        toastr.success(response.message);

                        $("#permissionForm")[0].reset();

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


                            $("." + key + "_error")
                                .text(value[0]);


                            toastr.error(value[0]);

                        });


                    } else {


                        let message =
                            xhr.responseJSON?.message
                            ?? "Something went wrong.";


                        toastr.error(message);


                        console.log(xhr.responseText);

                    }

                }

            });

        });

    });

</script>

@endpush
