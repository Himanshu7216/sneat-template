@extends('layouts.app')
@section('title', 'add Module')
@section('content')
    <div class="custom-breadcrumb-container">
        {{ Breadcrumbs::render('Add_Module') }}
    </div>

   <div class="container-xxl flex-grow-1 container-p-y">
    <div class="container">

        <div class="row justify-content-center">

            <div class="col-md-8">

                <div class="card">

                    <div class="card-header">
                        <h5 class="mb-0">Add Module Permission</h5>
                    </div>

                    <div class="card-body">

                        <form action="{{ route('store-module') }}" method="POST">
                            @csrf

                            {{-- Name --}}
                            <div class="mb-3">
                                <label class="form-label">Permission Name</label>

                                <input
                                    type="text"
                                    name="name"
                                    class="form-control @error('name') is-invalid @enderror"
                                    value="{{ old('name') }}"
                                    placeholder="e.g. Dashboard">

                                @error('name')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            {{-- Permission Key --}}
                            <div class="mb-3">
                                <label class="form-label">Permission Key</label>

                                <input
                                    type="text"
                                    name="perm_key"
                                    class="form-control @error('perm_key') is-invalid @enderror"
                                    value="{{ old('perm_key') }}"
                                    placeholder="e.g. dashboard">

                                <small class="text-muted">
                                    Unique key used in code. Example:
                                    dashboard, user_management, view_users
                                </small>

                                @error('perm_key')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            {{-- Type --}}
                            <div class="mb-3">
                                <label class="form-label">Type</label>

                                <select
                                    name="type"
                                    class="form-select @error('type') is-invalid @enderror">

                                    <option value="">Select Type</option>

                                    <option value="module"
                                        {{ old('type') == 'module' ? 'selected' : '' }}>
                                        Module
                                    </option>

                                    <option value="action"
                                        {{ old('type') == 'action' ? 'selected' : '' }}>
                                        Action
                                    </option>

                                </select>

                                @error('type')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            {{-- Buttons --}}
                            <div class="d-flex gap-2">

                                <button type="submit" class="btn btn-primary">
                                    Save Permission
                                </button>

                                <a href="{{ route('user-management') }}" class="btn btn-secondary">
                                    Cancel
                                </a>

                            </div>

                        </form>

                    </div>

                </div>

            </div>

        </div>

    </div>
</div>
@endsection
