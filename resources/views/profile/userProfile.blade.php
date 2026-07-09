@extends('layouts.app')
@section('content')

<div class="container mt-5">
    <div class="card shadow-sm border-0">
        <div class="card-body p-4">

            <h4 class="mb-4">Update Profile</h4>

            <form action="{{ route('profile.updateprofile.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <input type="hidden" name="user_id" value="{{ $user->id }}">
                <div class="row">

                    <!-- Name -->
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Full Name</label>
                        <input type="text"
                               name="name"
                               class="form-control"
                               placeholder="Enter Full Name"
                               value="{{ old('name', $user->name ?? '') }}">
                    </div>

                    <!-- Email -->
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Email Address</label>
                        <input type="email"
                               name="email"
                               class="form-control"
                               placeholder="Enter Email"
                               value="{{ old('email', $user->email ?? '') }}">
                    </div>

                    <!-- Phone -->
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Phone Number</label>
                        <input type="text"
                               name="phone"
                               class="form-control"
                               placeholder="Enter Phone Number"
                               value="{{ old('phone', $user->phone ?? '') }}">
                    </div>

                    {{-- <!-- Password -->
                    <div class="col-md-6 mb-3">
                        <label class="form-label">
                            Password
                            <small class="text-muted">(Leave blank if you don't want to change)</small>
                        </label>
                        <input type="password"
                               name="password"
                               class="form-control"
                               placeholder="New Password">
                    </div> --}}

                    <!-- DOB -->
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Date of Birth</label>
                        <input type="date"
                               name="dob"
                               class="form-control"
                               value="{{ old('dob', $user->dob ?? '') }}">
                    </div>

                    <!-- Gender -->
                    <div class="col-md-6 mb-3">
                        <label class="form-label d-block">Gender</label>

                        <div class="form-check form-check-inline">
                            <input class="form-check-input"
                                   type="radio"
                                   name="gender"
                                   value="Male"
                                   checked>

                            <label class="form-check-label">
                                Male
                            </label>
                        </div>

                        <div class="form-check form-check-inline">
                            <input class="form-check-input"
                                   type="radio"
                                   name="gender"
                                   value="Female">

                            <label class="form-check-label">
                                Female
                            </label>
                        </div>

                        <div class="form-check form-check-inline">
                            <input class="form-check-input"
                                   type="radio"
                                   name="gender"
                                   value="Other">

                            <label class="form-check-label">
                                Other
                            </label>
                        </div>
                    </div>

                    <!-- Address -->
                    <div class="col-12 mb-3">
                        <label class="form-label">Address</label>
                        <textarea name="address"
                                  class="form-control"
                                  rows="3"
                                  placeholder="Enter Address">{{ old('address', $user->address ?? '') }}</textarea>
                    </div>

                    <!-- Bio -->
                    <div class="col-12 mb-3">
                        <label class="form-label">Bio</label>
                        <textarea name="bio"
                                  class="form-control"
                                  rows="4"
                                  placeholder="Tell something about yourself">{{ old('bio', $user->bio ?? '') }}</textarea>
                    </div>

                    <!-- Profile Image -->
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Profile Image</label>
                        <input type="file"
                               name="profile_image"
                               class="form-control"
                               accept="image/*">
                    </div>

                    <!-- Preview -->
                    <div class="col-md-6 mb-3">
                        <label class="form-label d-block">Current Image</label>

                        {{-- @dd($user->profile_image) --}}
                        <img src="@if (auth()->user()->profile_image)
                      {{ asset('./profile_pic/'.auth()->user()->profile_image) ?? ''}}
                       @else
                        ../assets/img/avatars/1.png
                       @endif "
                             class="rounded-circle border"
     style="width:120px; height:120px; object-fit:cover;"
     alt="Profile Image">
                    </div>

                </div>

                <div class="mt-3">
                    <button type="submit" class="btn btn-primary px-4">
                        Update Profile
                    </button>

                    <button type="reset" class="btn btn-outline-secondary" onclick="history.back()">
                        Cancel
                    </button>
                </div>

            </form>

        </div>
    </div>
</div>
@endsection
