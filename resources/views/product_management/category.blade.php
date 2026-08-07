@extends('layouts.app')
@section('title', 'Category management')
@section('content')
    <div class="custom-breadcrumb-container">
        {{ Breadcrumbs::render('Category_Management') }}
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
                    Category Management
                </h5>
                <div>
                    {{-- <a href="{{ route('create-category') }}" class="btn btn-outline-primary">
                        Add Product
                    </a> --}}
                    <a href="{{ route('create-category') }}" class="btn btn-outline-primary">
                        {{-- <i class="bi bi-plus-circle"></i> --}}
                        Add Category
                    </a>
                </div>

            </div>

            <div class="card-body">

                <div class="table-responsive">

                    <table id="CategoryTable" class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th scope="col">NAME</th>
                                <th scope="col">DESCRIPTION</th>
                                <th scope="col">STATUS</th>
                                {{-- <th scope="col">PRICE</th> --}}
                                {{-- <th scope="col">Category</th> --}}
                                <th scope="col">Action</th>
                            </tr>
                        </thead>
                        <tbody>
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
            $('#CategoryTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('show-category') }}",
                columns: [
                    { data: 'name', name: 'name' },
                    { data: 'description', name: 'description' },
                    { data: 'status', name: 'status' },
                    // { data: 'price', name: 'price' },
                    // { data: 'category', name: 'category' },
                    { data: 'action', name: 'action', orderable: false, searchable: false }
                ]
            });

            // EDIT CATEGORY
            $(document).on('click', '.editBtn', function () {
                let id = $(this).data('id');
                window.location.href = "/category/" + id + "/edit";
            });

            $(document).on('click', '.deleteBtn', function () {
                let id = $(this).data('id');

                Swal.fire({
                    title: 'Delete Category',
                    text: 'Are you sure you want to delete this category?',
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
                        url: "/category/" + id,
                        type: "DELETE",
                        data: {
                            _token: "{{ csrf_token() }}"
                        },
                        dataType: "json",
                        success: function (response) {
                            if (response.status === 'success') {
                                Swal.fire('Deleted!', response.message, 'success');
                                $('#CategoryTable')
                                    .DataTable()
                                    .ajax.reload(null, false);
                            }
                        },
                        error: function (xhr) {
                            toastr.error(
                                xhr.responseJSON?.message ??
                                'Category delete failed.'
                            );
                        }
                    });
                });
            });
        });
    </script>
@endpush
