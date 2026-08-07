@extends('layouts.app')
@section('title', 'create Category')

@section('content')
    {{-- @include('components.breadcrumbs_navigation') --}}
    @include('notify::components.notify')
    <div class="custom-breadcrumb-container">
        {{ Breadcrumbs::render('Add_category') }}
    </div>
    <div class="container-xxl flex-grow-1 container-p-y">

        <div class="container-fluid">

            <div id="alert-box" class="mb-4"></div>

            <div class="row justify-content-center">

                <div class="col-lg-6 col-md-8 col-sm-12">

                    <div class="card shadow-sm">


                        <div class="card-header app-header d-flex justify-content-between align-items-center">

                            <h4 class="mb-0">
                                Create Category
                            </h4>

                            <a href="{{ url()->previous() }}" class="btn btn-outline-secondary btn-sm">
                                <i class="bx bx-arrow-back"></i>
                            </a>

                        </div>
                        <div class="card-body p-4">

                            <form id="addCategoryForm" action="/category/store" method="post" autocomplete="off">
                                @csrf

                                <div class="mb-3">
                                    <label for="categoryname" class="form-label">Category Name</label>
                                    <input type="text" class="form-control" name="categoryname" id="categoryname"
                                        placeholder="Enter category name" maxlength="50">
                                    <div class="text-danger error-categoryname categoryname_error"></div>
                                </div>
                                <div class="mb-3">
                                    <label for="description" class="form-label">Description</label>
                                    <textarea class="form-control" id="description" name="description" rows="3"
                                        placeholder="Enter description here.." maxlength="1000"></textarea>
                                    <div class="text-danger error-description description_error"></div>
                                </div>
                                <div class="mb-3">
                                    <label for="status" class="form-label">Status</label>
                                    <select class="form-select" id="status" name="status">
                                        <option value="">Select Status</option>
                                        <option value="active">Active</option>
                                        <option value="inactive">Inactive</option>
                                    </select>
                                    <div class="text-danger error-status status_error"></div>
                                </div>
                                <button type="submit" class="btn btn-primary">Add Category</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function () {
            $.ajaxSetup({
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
                },
            });

            toastr.options = {
                "closeButton": true,
                "progressBar": true,
                "positionClass": "toast-top-right",
                "preventDuplicates": true,
                "timeOut": "4000"
            };

            // Attach real-time input validations
            validateCategoryName("#categoryname");
            validateCategoryDescription("#description");
            validateCategoryStatus("#status");

            $('form, input').attr('autocomplete', 'off');

            $("#addCategoryForm").submit(function (e) {
                e.preventDefault();

                // Dynamic form validation before submit
                if (!validateCategoryForm("#addCategoryForm")) {
                    toastr.error("Please fill all required fields correctly.");
                    return false;
                }

                let formData = new FormData(this);

                $.ajax({
                    url: "/category/store",
                    type: "POST",
                    data: formData,
                    contentType: false,
                    processData: false,
                    dataType: "json",
                    success: function (response) {
                        if (response.status === "success") {
                            toastr.success(response.message || "Category created successfully!");
                            $("#addCategoryForm")[0].reset();
                            $(".text-danger").text("");
                        } else if (response.status === "error") {
                            toastr.error(response.message || "Failed to create category.");
                        }
                    },
                    error: function (xhr) {
                        $(".text-danger").text("");
                        if (xhr.status === 422) {
                            let errors = xhr.responseJSON.errors;
                            $.each(errors, function (key, value) {
                                $("." + key + "_error").text(value[0]);
                                $(".error-" + key).text(value[0]);
                                toastr.error(value[0]);
                            });
                        } else {
                            let message = xhr.responseJSON?.message ?? "Something went wrong.";
                            toastr.error(message);
                            console.log(xhr.responseText);
                        }
                    }
                });
            });
        });
    </script>
@endsection
