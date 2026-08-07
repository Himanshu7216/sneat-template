@extends('layouts.app')
@section('title', 'Edit Product')

@section('content')

    {{-- @include('components.breadcrumbs_navigation') --}}
    @include('notify::components.notify')
    <div class="custom-breadcrumb-container">
        {{ Breadcrumbs::render('Edit_Product',$product->id) }}
    </div>
    {{-- @dd('hii'); --}}
    <div class="container-xxl flex-grow-1 container-p-y">

        <div class="container-fluid">

            <div id="alert-box" class="mb-4"></div>

            <div class="row justify-content-center">

                <div class="col-lg-6 col-md-8 col-sm-12">

                    <div class="card shadow-sm">


                        <div class="card-header app-header d-flex justify-content-between align-items-center">

                            <h4 class="mb-0">
                                Edit Product
                            </h4>

                            <a href="{{ url()->previous() }}" class="btn btn-outline-secondary btn-sm">
                                <i class="bx bx-arrow-back"></i>
                            </a>

                        </div>
                        <div class="card-body p-4">
                            {{-- @dd($product) --}}

                            <form id="EditProductForm" action="/products/{{ $product->id }}/update" method="post"
                                enctype="multipart/form-data">
                                @csrf

                                <div class="mb-3">
                                    <label for="productname" class="form-label">Product Name</label>
                                    <input type="text" class="form-control" name="productname" id="productname"
                                        placeholder="Enter product name" maxlength="100" value="{{ old('productname', $product->name) }}">
                                    <div class="text-danger error-productname"></div>
                                </div>

                                <div class="mb-3">
                                    <label for="sku" class="form-label">SKU</label>
                                    <input type="text" class="form-control" name="sku" id="sku" placeholder="Enter SKU"
                                        maxlength="100" value="{{ old('sku', $product->sku) }}">
                                    <div class="text-danger error-sku"></div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="color" class="form-label">Color</label>
                                        <input type="text" class="form-control" name="color" id="color"
                                            placeholder="Enter product color" maxlength="50" value="{{ old('color', $product->color) }}">
                                        <div class="text-danger error-color"></div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="size" class="form-label">Size</label>
                                        <input type="text" class="form-control" name="size" id="size"
                                            placeholder="Enter product size" maxlength="50" value="{{ old('size', $product->size) }}">
                                        <div class="text-danger error-size"></div>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="description" class="form-label">Description</label>
                                    <textarea class="form-control" id="description" name="description" rows="3"
                                        maxlength="1000">{{ old('description', $product->description) }}</textarea>
                                    <div class="text-danger error-description"></div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="price" class="form-label">Price</label>
                                        <input type="text" class="form-control" name="price" id="price"
                                            placeholder="Enter product price" maxlength="10" value="{{ old('price', $product->price) }}">
                                        <div class="text-danger error-price"></div>
                                    </div>
                                    {{-- @dd($categories->name) --}}
                                    <div class="col-md-6 mb-3">
                                        <label for="category" class="form-label">Category</label>
                                        <select class="form-select" id="category" name="category">
                                            <option value="">Select Category</option>
                                            @foreach ($categories as $category)
                                                <option value="{{ $category->id }}"
                                                    {{ $product->category_id == $category->id ? 'selected' : '' }}>
                                                    {{ $category->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <div class="text-danger error-category"></div>
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <label for="image" class="form-label">Product Image</label>
                                    <div id="dropzone">
                                        <div id="editImageDropzone" class="dropzone">
                                            <div class="dz-message needsclick">
                                                Drop files here or click to upload.
                                            </div>
                                        </div>
                                    </div>
                                    <div class="text-danger error-uploaded_images mt-1"></div>
                                </div>

                                <button type="submit" class="btn btn-primary">Update Product</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        Dropzone.autoDiscover = false;

        $(document).ready(function () {
            $.ajaxSetup({
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
                },
            });

            toastr.options.preventDuplicates = true;

            // ── Field Validations ──────────────────────────────────────────────

            let productNamePattern = /^[A-Za-z\s]{3,50}$/;

            $("#productname").on("keypress", function (e) {
                let char = String.fromCharCode(e.which);
                if (!/^[a-zA-Z\s]+$/.test(char)) {
                    e.preventDefault();
                }
            });
            $("#productname").on("input blur", function () {
                this.value = this.value.replace(/[^a-zA-Z\s]/g, "").slice(0, 50);
                let productName = $(this).val().trim();
                if (productName === "") {
                    $(".error-productname").text("Product Name is required");
                } else if (!productNamePattern.test(productName)) {
                    $(".error-productname").text("Only letters allowed (3-50 characters)");
                } else {
                    $(".error-productname").text("");
                }
            });

            // DESCRIPTION VALIDATION
            $("#description").on("input blur", function () {
                let description = $(this).val().trim();
                if (description === "") {
                    $(".error-description").text("Description is required");
                } else if (description.length > 1000) {
                    $(".error-description").text("Description cannot exceed 1000 characters");
                } else {
                    $(".error-description").text("");
                }
            });

            let pricePattern = /^[0-9]*\.?[0-9]*$/;

            $("#price").on("keypress", function (e) {
                let char = String.fromCharCode(e.which);
                if (!/[0-9.]/.test(char)) {
                    e.preventDefault();
                }
                if (char === "." && $(this).val().includes(".")) {
                    e.preventDefault();
                }
            });

            $("#price").on("input blur", function () {
                let price = $(this).val().trim();
                if (price === "") {
                    $(".error-price").text("Price is required");
                } else if (!pricePattern.test(price)) {
                    $(".error-price").text("Only integer or decimal values allowed");
                } else {
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
            // uploads/products
            // ── Dropzone Setup ─────────────────────────────────────────────────
            // console.log(imageUrl);


            // Existing images stored on the product (cast to array by Laravel)
            let existingImages = @json($product->image ?? []);

            let uploadedImages = [];

            let myDropzone = new Dropzone("#editImageDropzone", {
                url: "{{ route('products.uploadImage') }}",
                paramName: "image",
                clickable: true,
                maxFiles: 5,
                maxFilesize: 3,          // MB
                acceptedFiles: ".jpg,.jpeg,.png,.gif,.webp",
                addRemoveLinks: true,
                parallelUploads: 5,
                autoProcessQueue: true,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },

                success: function (file, response) {
                    // New file uploaded — track its filename
                    if (response && response.filename) {
                        file._uploadedFilename = response.filename;
                        uploadedImages.push(response.filename);
                    }
                    console.log('Uploaded:', response);
                },

                removedfile: function (file) {
                    // Remove from tracking array whether it's a new or existing image
                    let key = file._uploadedFilename || file._existingFilename;
                    if (key) {
                        uploadedImages = uploadedImages.filter(f => f !== key);
                    }
                    if (file.previewElement) {
                        file.previewElement.remove();
                    }
                },

                error: function (file, response) {
                    let msg = (typeof response === 'string') ? response : (response.message || 'Image upload failed.');
                    toastr.error(msg);
                    console.log('Dropzone error:', response);
                }
            });

            // Pre-populate Dropzone with existing product images
            function loadExistingPreview(filename, callback) {
                const encodedName = encodeURIComponent(filename);
                const candidates = [
                    "{{ asset('storage/Products') }}/" + encodedName,
                    "{{ asset('uploads/products') }}/" + encodedName
                ];

                function tryNext() {
                    if (candidates.length === 0) {
                        callback(null);
                        return;
                    }
                    const url = candidates.shift();
                    const img = new Image();
                    img.onload = function () {
                        callback(url);
                    };
                    img.onerror = function () {
                        tryNext();
                    };
                    img.src = url;
                }

                tryNext();
            }

            existingImages.filter(Boolean).forEach(function (filename) {
                loadExistingPreview(filename, function (imageUrl) {
                    if (!imageUrl) {
                        return;
                    }

                    let mockFile = {
                        name: filename,
                        size: 0,
                        accepted: true,
                        _existingFilename: filename
                    };

                    mockFile.status = Dropzone.SUCCESS;
                    mockFile.accepted = true;
                    myDropzone.emit("addedfile", mockFile);
                    myDropzone.emit("thumbnail", mockFile, imageUrl);
                    myDropzone.emit("complete", mockFile);
                    myDropzone.files.push(mockFile);

                    // Track this existing image as one that should be kept
                    uploadedImages.push(filename);
                });
            });

            // ── Form Submit ────────────────────────────────────────────────────

            $("#EditProductForm").submit(function (e) {
                e.preventDefault();

                // Guard: at least one image must be present
                if (uploadedImages.length === 0) {
                    toastr.error('Image field is required. Please upload at least one product image.');
                    return;
                }

                let formData = new FormData(this);

                // Send all surviving filenames (existing + newly uploaded)
                uploadedImages.forEach(function (filename) {
                    formData.append('uploaded_images[]', filename);
                });

                $.ajax({
                    url: "{{ route('update-products', $product->id) }}",
                    type: "POST",
                    data: formData,
                    contentType: false,
                    processData: false,
                    dataType: "json",
                    success: function (response) {
                        if (response.status === "success") {
                            Swal.fire({
                                title: 'Updated!',
                                text: response.message,
                                icon: 'success',
                                confirmButtonText: 'OK'
                            }).then(function () {
                                window.location.href = "{{ route('show-products') }}";
                            });
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
    @endpush
@endsection
