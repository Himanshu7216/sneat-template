@extends('layouts.app')
@section('title','Update Profile')

@section('content')
{{-- @include('components.breadcrumbs_navigation') --}}
    @include('notify::components.notify')
    <div class="custom-breadcrumb-container">
    {{ Breadcrumbs::render('profile') }}
</div>
<div class="container mt-5">
    <div class="card shadow-sm border-0">
        <div class="card-body p-4">

            <h4 class="mb-4">Update Profile</h4>

            <form id="updateProfileForm" action="{{ route('profile.updateprofile.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <input type="hidden" name="user_id" value="{{ $user->id }}">
                <div class="row">

                    <!-- Name -->
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Full Name</label>
                        <input type="text"
                               name="name"
                               id="name"
                               class="form-control"
                               placeholder="Enter Full Name"
                               value="{{ old('name', $user->name ?? '') }}">
                               <span class="text-danger error-text name_error"></span>
                    </div>

                    <!-- Email -->
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Email Address</label>
                        <input type="email"
                               name="email"
                               id="email"
                               style="background: rgba(255, 169, 48, 0.381)"
                               class="form-control" maxlength="50" minlength="5" autocomplete="email"
                               placeholder="Enter Email"
                               value="{{ old('email', $user->email ?? '') }}" readonly>
                    </div>

                    <!-- Phone -->
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Phone Number</label>
                        <input type="text"
                               name="phone"
                               id="phone"
                               class="form-control"
                               placeholder="Enter Phone Number"
                               maxlength="10"
                               value="{{ old('phone', $user->phone ?? '') }}">
                               <span class="text-danger error-text phone_error"></span>
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
                               id="dob"
                               class="form-control"
                               max="{{ date('Y-m-d') }}"
                               value="{{ old('dob', $user->dob ?? '') }}">
                                <span class="text-danger error-text dob_error"></span>
                    </div>

                    <!-- Gender -->
                    <div class="col-md-6 mb-3">
                        <label class="form-label d-block">Gender</label>

                        <div class="form-check form-check-inline">
                            <input class="form-check-input"
                                   type="radio"
                                   name="gender"
                                   id="gender_male"
                                   value="male"
                                   {{ old('gender', $user->gender ?? '') == 'male' ? 'checked' : '' }}>

                            <label class="form-check-label" for="gender_male">
                                Male
                            </label>
                        </div>

                        <div class="form-check form-check-inline">
                            <input class="form-check-input"
                                   type="radio"
                                   name="gender"
                                   id="gender_female"
                                   value="female"
                                   {{ old('gender', $user->gender ?? '') == 'female' ? 'checked' : '' }}>

                            <label class="form-check-label" for="gender_female">
                                Female
                            </label>
                        </div>

                        <div class="form-check form-check-inline">
                            <input class="form-check-input"
                                   type="radio"
                                   id="gender_other"
                                   name="gender"
                                   value="other"
                                   {{ old('gender', $user->gender ?? '') == 'other' ? 'checked' : '' }}>

                            <label class="form-check-label" for="gender_other">
                                Other
                            </label>
                        </div>
                        <span class="text-danger error-text gender_error"></span>
                    </div>

                    <!-- Address -->
                    <div class="col-12 mb-3">
                        <label class="form-label">Address</label>
                        <textarea name="address"
                                  class="form-control"
                                  rows="3"
                                  id="address"
                                  maxlength="255"
                                  placeholder="Enter Address">{{ old('address', $user->address ?? '') }}</textarea>
                                  <small class="text-danger address_error"></small>
                    </div>

                    <!-- Bio -->
                    <div class="col-12 mb-3">
                        <label class="form-label">Bio</label>
                        <textarea name="bio"
                                  class="form-control"
                                  id="bio"
                                  rows="4"
                                  maxlength="500"
                                  placeholder="Tell something about yourself">{{ old('bio', $user->bio ?? '') }}</textarea>
                                  <small class="text-danger bio_error"></small>
                    </div>

                    <!-- Profile Image -->
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Profile Image</label>
                        <input type="file"
                               name="profile_image"
                               id="profile_image"
                               class="form-control"
                               accept="image/*">
                    </div>
                    <span class="text-danger error-text profile_image_error"></span>

                    <!-- Preview -->
                    <div class="col-md-6 mb-3">
                        <label class="form-label d-block">Current Image</label>

                        {{-- @dd($user->profile_image) --}}
                        <img src="@if (auth()->user()->profile_image)
                      {{ asset('profile_pic/' . auth()->user()->profile_image) }}
                       @else
                        {{ asset('assets/img/avatars/1.png') }}
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

                    <button type="reset" class="btn btn-outline-secondary" href="{{ route('dashboard') }}" id="cancleUpdate">
                        Cancel
                    </button>
                </div>

            </form>

        </div>
    </div>
</div>
<script src="{{ asset('js/userProfile.js') }}"></script>
@endsection
