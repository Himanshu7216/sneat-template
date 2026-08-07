@extends('layouts.app')
@section('title', 'create product')

@section('content')

    {{-- @include('components.breadcrumbs_navigation') --}}
    @include('notify::components.notify')
    <div class="custom-breadcrumb-container">
        {{ Breadcrumbs::render('Add_Product') }}
    </div>
    <div class="container-xxl flex-grow-1 container-p-y">

        <div class="container-fluid">

            <div id="alert-box" class="mb-4"></div>

            <div class="row justify-content-center">

                <div class="col-lg-6 col-md-8 col-sm-12">

                    <div class="card shadow-sm">


                        <div class="card-header app-header d-flex justify-content-between align-items-center">

                            <h4 class="mb-0">
                                Create product
                            </h4>

                            <a href="{{ url()->previous() }}" class="btn btn-outline-secondary btn-sm">
                                <i class="bx bx-arrow-back"></i>
                            </a>

                        </div>
                        <div class="card-body p-4">

                            <form id="createNewProduct" action="/products/store" method="post"
                                enctype="multipart/form-data">
                                @csrf

                                <div class="mb-3">
                                    <label for="productname" class="form-label">Product Name</label>
                                    <input type="text" class="form-control" name="productname" id="productname"
                                        placeholder="Enter product name" maxlength="100">
                                    <div class="text-danger error-productname"></div>
                                    {{-- @if($errors->has('productname'))
                                    <div class="error text-danger">{{ $errors->first('productname') }}</div>
                                    @endif --}}
                                </div>

                                <div class="mb-3">
                                    <label for="sku" class="form-label">SKU</label>
                                    <input type="text" class="form-control" name="sku" id="sku" placeholder="Enter SKU"
                                        maxlength="100">

                                    <div class="text-danger error-sku"></div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 mb-3"> <label for="color" class="form-label">Color</label> <input
                                            type="text" class="form-control" name="color" id="color"
                                            placeholder="Enter product color" maxlength="50">
                                        <div class="text-danger error-color"></div>
                                    </div>
                                    <div class="col-md-6 mb-3"> <label for="size" class="form-label">Size</label> <input
                                            type="text" class="form-control" name="size" id="size"
                                            placeholder="Enter product size" maxlength="50">
                                        <div class="text-danger error-size"></div>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="description" class="form-label">Description</label>
                                    <textarea class="form-control" id="description" name="description" rows="3"
                                        maxlength="1000"></textarea>
                                    <div class="text-danger error-description"></div>
                                    {{-- @if($errors->has('description'))
                                    <div class="error text-danger">{{ $errors->first('description') }}</div>
                                    @endif --}}
                                </div>
                                <div class="row">
                                    <div class="col-md-6 mb-3"> <label for="price" class="form-label">Price</label> <input
                                            type="text" class="form-control" name="price" id="price"
                                            placeholder="Enter product price" maxlength="10">
                                        <div class="text-danger error-price"></div>
                                    </div>
                                    <div class="col-md-6 mb-3"> <label for="category" class="form-label">Category</label>
                                        <select class="form-select" id="category" name="category">
                                            <option value="">Select Category</option> @foreach ($categories as $category)
                                                @if ($category->status === "active")
                                                <option value="{{ $category->id }}"> {{ $category->name }} </option> @endif
                                            @endforeach
                                        </select>
                                        <div class="text-danger error-category"></div>
                                    </div>
                                </div>
                                <div class="mb-4">
                                    <label for="image" class="form-label">Product Image</label>
                                    {{-- <input type="file" class="form-control" name="image[]" id="image" multiple>
                                    <div class="text-danger error-image"></div> --}}
                                    <DIV id="dropzone">
                                        <div id="imageDropzone" class="dropzone">
                                            <div class="dz-message needsclick">
                                                Drop files here or click to upload.
                                            </div>
                                        </div>

                                    </DIV>

                                </div>
                                <button type="submit" class="btn btn-primary">Create Product</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        Dropzone.autoDiscover = false;
        $(document).ready(function () {
            $.ajaxSetup({
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
                },
            });

            toastr.options.preventDuplicates = true;

            let productNamePattern = /^[A-Za-z\s]{3,50}$/;


            $("#productname").on("input blur", function () {
                let productName = $(this).val().trim();
                if (productName === "") {
                    $(".error-productname").text("Product Name is required");

                } else {
                    $(".error-productname").text("");
                }
            });


            // DESCRIPTION VALIDATION
            $("#description").on("input blur", function () {

                let description = $(this).val().trim();

                if (description === "") {
                    $(".error-description").text("Description is required");
                }
                else if (description.length > 1000) {
                    $(".error-description").text("Description cannot exceed 1000 characters");
                }
                else {
                    $(".error-description").text("");
                }

            });


            let pricePattern = /^[0-9]*\.?[0-9]*$/;

            // Allow only numbers and one decimal point
            $("#price").on("keypress", function (e) {
                let char = String.fromCharCode(e.which);

                if (!/[0-9.]/.test(char)) {
                    e.preventDefault();
                }

                // prevent multiple dots
                if (char === "." && $(this).val().includes(".")) {
                    e.preventDefault();
                }
            });

            $("#price").on("input blur", function () {
                let price = $(this).val().trim();

                if (price === "") {
                    $(".error-price").text("Price is required");
                }
                else if (!pricePattern.test(price)) {
                    $(".error-price").text("Only integer or decimal values allowed");
                }
                else {
                    $(".error-price").text("");
                }
            });

            $("#category").on("change blur", function () {
                let category = $(this).val();

                if (category === "" || category === null) {
                    $(".error-category").text("Category is required");
                } else {
                    $(".error-category").text("");
                }
            });



                // Track filenames uploaded by Dropzone
            let uploadedImages = [];

            let myDropzone = new Dropzone("#imageDropzone", {

                url: "{{ route('products.uploadImage') }}",

                    paramName: "image", // The name that will be used to transfer the file

                clickable: true,

                    maxFiles: 5,    // Maximum number of files

                    maxFilesize: 3, // Maximum file size in MB

                    acceptedFiles: ".jpg,.jpeg,.png,.gif,.webp", // Accepted file types

                    addRemoveLinks: true, // Show remove links on each file preview

                    parallelUploads: 5,    // Number of files to upload in parallel

                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },

                success: function (file, response) {
                    // Store the server-returned filename so we can pass it on form submit
                    if (response && response.filename) {
                        file._uploadedFilename = response.filename;
                        uploadedImages.push(response.filename);
                    }
                    console.log('Uploaded:', response);
                },

                removedfile: function (file) {
                    // Remove from tracking array when user removes a file
                    if (file._uploadedFilename) {
                        uploadedImages = uploadedImages.filter(f => f !== file._uploadedFilename);
                    }
                    file.previewElement && file.previewElement.remove();
                },

                error: function (file, response) {
                    let msg = (typeof response === 'string') ? response : (response.message || 'Image upload failed.');
                    toastr.error(msg);
                    console.log('Dropzone error:', response);
                }

            });


            $("#createNewProduct").submit(function (e) {
                e.preventDefault();

                // Client-side guard: at least one image must be uploaded via Dropzone
                if (uploadedImages.length === 0) {
                    toastr.error('Image field is required. Please upload at least one product image.');
                    return;
                }

                let formData = new FormData(this);

                // Append each Dropzone-uploaded filename so the server can locate the files
                uploadedImages.forEach(function (filename) {
                    formData.append('uploaded_images[]', filename);
                });

                $.ajax({
                    url: "/products/store",
                    type: "POST",
                    data: formData,
                    contentType: false,
                    processData: false,
                    dataType: "json",
                    success: function (response) {
                        if (response.status === "success") {
                            toastr.success(response.message);

                            // Reset the form fields
                            $("#createNewProduct")[0].reset();

                            // Clear all Dropzone previews and the tracking array
                            myDropzone.removeAllFiles(true);
                            uploadedImages = [];

                            // Clear any inline field errors
                            $(".text-danger").text("");
                        } else if (response.status === "error") {
                            toastr.error(response.message);
                        }
                    },
                    error: function (xhr) {
                        $(".text-danger").text("");
                        if (xhr.status === 422) {
                            let errors = xhr.responseJSON.errors;
                            $.each(errors, function (key, value) {
                                $(".error-" + key).text(value[0]);
                                $("." + key + "_error").text(value[0]);
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
