@extends('layouts.app')
@section('title', 'Product management')
@section('content')
    <div class="custom-breadcrumb-container">
        {{ Breadcrumbs::render('Product_Management') }}
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
                    Product Management
                </h5>
                <div>
                    <a href="{{ route('create-product') }}" class="btn btn-outline-primary">
                        {{-- <i class="bi bi-plus-circle"></i> --}}
                        Add Product
                    </a>
                </div>

            </div>

            <div class="card-body">

                <div class="table-responsive">

                    <table id="ProductTable" class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th scope="col">SKU</th>
                                <th scope="col">NAME</th>
                                <th scope="col">CATEGORY</th>
                                <th scope="col">COLOR</th>
                                <th scope="col">SIZE</th>
                                <th scope="col">PRICE</th>
                                {{-- <th scope="col">Category</th> --}}
                                <th scope="col">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            {{-- @foreach($data as $product)
                            <tr id="productRow{{ $product->id }}">
                                <td scope="row">{{ $product->sku }}</td>
                                <td>{{ $product->name }}</td>
                                <td>{{ $product->color }}</td>
                                <td>{{ $product->size }}</td>
                                <td>₹{{ $product->price }}</td> --}}

                                {{-- <td><img src="{{ asset('storage/products/' . $product->image) }}"
                                        alt="{{ $product->name }}" width="100">
                                </td> --}}
                                {{-- <td>{{ $product->category->name ?? 'No Category' }}</td> --}}
                                {{-- <td>
                                    <a href="/products/edit/{{ $product->id }}"
                                        class="btn btn-sm btn-outline-warning">Update</a>

                                    <form class="deleteProductForm" data-id="{{ $product->id }}" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger">
                                            Delete
                                        </button>
                                    </form>
                                </td> --}}
                                {{--
                            </tr>
                            @endforeach --}}
                        </tbody>
                    </table>

                </div>

            </div>
        </div>
    </div>
@endsection

@push('scripts')

    <script>
        $(document).ready(function () {
            $('#ProductTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('show-products') }}",
                columns: [
                    { data: 'sku', name: 'sku' },
                    { data: 'name', name: 'name' },
                    { data: 'category', name: 'category', orderable: true, searchable: true },
                    { data: 'color', name: 'color' },
                    { data: 'size', name: 'size' },
                    { data: 'price', name: 'price' },
                    // { data: 'category', name: 'category' },
                    { data: 'action', name: 'action', orderable: false, searchable: false }
                ]
            });


            // EDIT CATEGORY
            $(document).on('click', '.editBtn', function () {
                let id = $(this).data('id');
                window.location.href = "/products/" + id + "/edit";
            });



            $(document).on('click', '.deleteBtn', function () {
                let id = $(this).data('id');

                Swal.fire({
                    title: 'Delete Product',
                    text: 'Are you sure you want to delete this product?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (!result.isConfirmed) {
                        return;
                    }

                    $.ajax({
                        url: "/products/" + id,
                        type: "DELETE",
                        data: {
                            _token: "{{ csrf_token() }}"
                        },
                        dataType: "json",
                        success: function (response) {
                            if (response.status === 'success') {
                                Swal.fire('Deleted!', response.message, 'success');
                                $('#ProductTable')
                                    .DataTable()
                                    .ajax.reload(null, false);
                            }
                        },
                        error: function (xhr) {
                            toastr.error(
                                xhr.responseJSON?.message ??
                                'Product delete failed.'
                            );
                        }
                    });
                });
            });
        });
    </script>
@endpush
