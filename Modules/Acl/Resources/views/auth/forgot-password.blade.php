@extends('Acl::auth.master')

@section('content')
    <p>{{ __('Forgot Password') }}:</p>
    {!! Form::open(['route' => 'access.password.email', 'class' => 'login-form']) !!}
        <p>{!! trans('Acl::auth.forgot_password.message') !!}</p>
    <br>
        <div class="form-group">
            <label>{{ trans('Acl::auth.login.email') }}</label>
            {!! Form::text('email', old('email'), ['class' => 'form-control', 'placeholder' => trans('Acl::auth.login.email')]) !!}
        </div>
        <button type="submit" class="btn btn-block login-button">
            <span class="signin">{{ trans('Acl::auth.forgot_password.submit') }}</span>
        </button>
        <div class="clearfix"></div>

        <br>
        <p><a class="lost-pass-link" href="{{ route('access.login') }}">{{ trans('Acl::auth.back_to_login') }}</a></p>
    {!! Form::close() !!}
@stop
