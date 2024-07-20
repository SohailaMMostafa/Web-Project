@extends('layouts.app')

@section('title', 'Responsive Registration Form')

@section('content')
    <div class="container">
        <header class="form_title">@lang('messages.registration_title')</header>
        <form id="registrationForm" action=" url('/register')" method="post" enctype="multipart/form-data">
            @csrf
            <div class="form">
                <label class="title"> @lang('messages.personal_details')</label>

                <div class="fields">
                    <div class="input-field">
                        <label> @lang('messages.full_name')</label>
                        <input type="text" name="full_name" placeholder="Enter your name"
                               required>
                        <span id="full_name-error" class="full_name-error"></span>
                    </div>

                    <div class="input-field username-container">
                        <label> @lang('messages.username')</label>
                        <input id="username" type="text" name="username" placeholder="Enter your username" required>
                        <span id="username-message" class="username-message"></span>
                    </div>

                    <div class="input-field">
                        <label> @lang('messages.email')</label>
                        <input type="email" id="email" name="email" placeholder="Enter your email" required>
                        <span class="email-error" id="email-error"></span>
                    </div>

                    <div class="input-field">
                        <label> @lang('messages.mobile_number')</label>
                        <input type="tel" name="mobile_number" placeholder="Enter your mobile number" required>
                        <span class="mobile_number-error" id="mobile_number-error"></span>
                    </div>


                    <div class="input-field has-error">
                        <label> @lang('messages.password')</label>
                        <input type="password" name="password" id="password" placeholder="Enter your password"
                               required>
                        <span id="password-error" class="password-error"></span>
                    </div>

                    <div class="input-field">
                        <label> @lang('messages.confirm_password')</label>
                        <input type="password" name="password_confirmation" id="confirmation_password"
                               placeholder="Confirm your password" required>
                        <span id="confirmation_password-error" class="password-error"></span>
                    </div>

                    <!-- <div class="error">
                        <span id="passwordMessage" class="error"></span>
                    </div> -->


                    <div class="input-field">
                        <label for="birthdate"> @lang('messages.date_of_birth')</label>
                        <div class="date-input-container">
                            <input type="date" id="birthdate" name="date_of_birth" placeholder="Enter birth date"
                                   min="1900-01-01" required>
                            <button class="show-popup" id="showDatePicker" type="button">@lang('messages.check')</button>
                        </div>
                    </div>


                    <div class="input-field">
                        <label> @lang('messages.address')</label>
                        <input type="text" name="address" placeholder="Enter your address" required>
                        <span id="address-error" class="address-error"></span>
                    </div>


                    <div class="photo">
                        <label> @lang('messages.upload_photo')</label><br>
                        <input type="file" name="user_image" accept="image/*" required>
                    </div>

                    <div id="registration-message">
                        <span id="registration-status"></span>
                    </div>

                    <div>
                        <button class="btnText">
                            @lang('messages.submit')
                            <i class="uil uil-navigator"></i>
                        </button>
                    </div>

                    <div class="popup-container">
                        <div class="popup-box">
                            <h1> @lang('messages.welcome')</h1>
                            <div id="imagesContainer"></div>
                            <button class="close-btn">@lang('messages.done')</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection
