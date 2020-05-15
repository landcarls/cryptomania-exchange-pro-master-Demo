@extends('backend.layouts.no_header_layout')
@section('title', company_name())
@section('centralize-content')       
<div class="login-box">
        <h2 align="center"> Sign In </h2>
        <!-- /.login-logo -->
        <div class="login-box-body">
            {{ Form::open(['route'=>'login', 'medthod' => 'post','class' => 'login-form']) }}
            <input type="hidden" value="{{base_key()}}" name="base_key">
            <div class="form-group {{ $errors->has('username') ? 'has-error' : '' }}">
                <div>
                    {{ Form::text(fake_field('username'), null, ['class'=>'form-control', 'placeholder' => __('Username'),'data-cval-name' => 'Username','data-cval-rules' => 'required']) }}
                    <span class="glyphicon glyphicon-user form-control-feedback"></span>
                </div>
                <span class="validation-message cval-error" data-cval-error="{{ fake_field('username') }}">{{ $errors->first('username') }}</span>
            </div>

            <div class="form-group {{ $errors->has('password') ? 'has-error' : '' }}">
                <div>
                    {{ Form::input('password',fake_field('password'), null,['class'=>'form-control', 'placeholder' => __('Password'),'data-cval-name' => 'Password','data-cval-rules' => 'required']) }}
                    <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                </div>
                <span class="validation-message cval-error" data-cval-error="{{ fake_field('password') }}">{{ $errors->first('password') }}</span>
            </div>

            <div class="clearfix link-after-form-right">
                <a href="{{ route('forget-password.index') }}" class="pull-right link-underline">{{ __('Forget Password') }}</a>
                @if(admin_settings('require_email_verification') == ACTIVE_STATUS_ACTIVE)
                    <a href="{{ route('verification.form') }}" class="text-center pull-right link-underline">{{ __('Get verification email') }}</a>
                @endif
            </div>

            @if( env('APP_ENV') != 'local' && admin_settings('display_google_captcha') == ACTIVE_STATUS_ACTIVE )
                <div class="form-group {{ $errors->has('g-recaptcha-response') ? 'has-error' : '' }}">
                    <div>
                        {!! NoCaptcha::display() !!}
                    </div>
                    <span class="validation-message cval-error">{{ $errors->first('g-recaptcha-response') }}</span>
                </div>
            @endif

            <div class="row">
                <div class="col-xs-7">
                    <div class="checkbox">
                        <label>
                            {{ Form::checkbox(fake_field('remember_me'), 1, false) }}
                            {{ __('Remember Me') }}
                        </label>
                    </div>
                </div>
                <div align="center">{{ Form::submit(__('Sign In'), ['class'=>'btn btn-primary ']) }}</div>
            </div>
            {{ Form::close() }}
        </div>
        <!-- /.login-box-body -->
    </div>
    <br>
    <p align="center">Don't have an account? <a href="{{ route('register.index') }}">Sign up here</a></p>

@endsection

@section('script')
    <script src="{{ asset('common/vendors/cvalidator/cvalidator.js') }}"></script>
    <script>
        $(document).ready(function () {
            $('.login-form').cValidate({});
        });
    </script>
@endsection