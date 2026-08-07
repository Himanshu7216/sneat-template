@extends('layouts.app')

@section('title', 'Edit Category')

@section('content')
    @include('notify::components.notify')
    <div class="custom-breadcrumb-container">
        {{ Breadcrumbs::render('Edit_category', $category->id) }}
    </div>

    <div class="container-xxl flex-grow-1 container-p-y">

        <div class="container-fluid">

            <div id="alert-box" class="mb-4"></div>

            <div class="row justify-content-center">

                <div class="col-lg-6 col-md-8 col-sm-12">

                    <div class="card shadow-sm">


                        <div class="card-header app-header d-flex justify-content-between align-items-center">

                            <h4 class="mb-0">
                                Edit Category
                            </h4>

                            <a href="{{ url()->previous() }}" class="btn btn-outline-secondary btn-sm">
                                <i class="bx bx-arrow-back"></i>
                            </a>

                        </div>
                        <div class="card-body p-4">

                             <form id="editCategoryForm">

                @csrf

                <div class="mb-3">
                    <label for="categoryname" class="form-label">
                        Category Name
                    </label>

                    <input
                        type="text"
                        class="form-control"
                        id="categoryname"
                        name="categoryname"
                        value="{{ old('categoryname', $category->name) }}"
                        maxlength="50"
                    >

                    <div class="text-danger error-text error-categoryname categoryname_error"></div>
                </div>


                <div class="mb-3">
                    <label for="description" class="form-label">
                        Description
                    </label>

                    <textarea
                        class="form-control"
                        id="description"
                        name="description"
                        rows="4"
                        maxlength="1000"
                    >{{ old('description', $category->description) }}</textarea>

                    <div class="text-danger error-text error-description description_error"></div>
                </div>


                <div class="mb-3">
                    <label for="status" class="form-label">
                        Status
                    </label>

                    <select
                        class="form-select"
                        id="status"
                        name="status"
                    >
                        <option value="active"
                            {{ old('status', $category->status) == 'active' ? 'selected' : '' }}>
                            Active
                        </option>

                        <option value="inactive"
                            {{ old('status', $category->status) == 'inactive' ? 'selected' : '' }}>
                            Inactive
                        </option>
                    </select>

                    <div class="text-danger error-text error-status status_error"></div>
                </div>


                <button type="submit" class="btn btn-primary">
                    Update Category
                </button>

                <a href="{{ route('show-category') }}"
                   class="btn btn-secondary">
                    Cancel
                </a>

            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>







@push('scripts')

<script>

$(document).ready(function () {

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

    $('#editCategoryForm').submit(function (e) {

        e.preventDefault();

        // Call dynamic category form validation function from validation-helper.js
        if (!validateCategoryForm("#editCategoryForm")) {
            toastr.error("Please fill all required fields correctly.");
            return false;
        }

        let formData = new FormData(this);

        $.ajax({

            url: "{{ route('update-category', $category->id) }}",

            type: "POST",

            data: formData,

            contentType: false,

            processData: false,

            dataType: "json",

            success: function (response) {

                if (response.status === 'success') {

                    toastr.success(response.message || "Category updated successfully!");

                    setTimeout(function () {
                        window.location.href = "{{ route('show-category') }}";
                    }, 1000);
                } else if (response.status === 'error') {
                    toastr.error(response.message || "Failed to update category.");
                }
            },

            error: function (xhr) {
                $('.error-text').text('');

                if (xhr.status === 422) {

                    let errors = xhr.responseJSON.errors;

                    $.each(errors, function (field, messages) {

                        $('.' + field + '_error').text(messages[0]);
                        $('.error-' + field).text(messages[0]);

                    });

                    toastr.error('Please fix the validation errors.');

                } else {

                    toastr.error(
                        xhr.responseJSON?.message ??
                        'Something went wrong.'
                    );
                }
            }

        });

    });

});

</script>

@endpush

@endsection
