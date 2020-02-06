@extends('Acl::auth.master')

@section('content')
    <p>{{ __('Sign In Below') }}:</p>

    {!! Form::open(['route' => 'access.login', 'class' => 'login-form']) !!}
        <div class="form-group">
            <label>{{ trans('Acl::auth.login.username') }}</label>
            {!! Form::text('username', old('username', app()->environment('demo') ? 'Modules' : null), ['class' => 'form-control', 'placeholder' => trans('Acl::auth.login.username')]) !!}
        </div>

        <div class="form-group">
            <label>{{ trans('Acl::auth.login.password') }}</label>
            {!! Form::input('password', 'password', (app()->environment('demo') ? '159357' : null), ['class' => 'form-control', 'placeholder' => trans('Acl::auth.login.password')]) !!}
        </div>

        <div>
            <label>
                {!! Form::checkbox('remember', '1', true, ['class' => 'hrv-checkbox']) !!} {{ trans('Acl::auth.login.remember') }}
            </label>
        </div>
        <br>

        <button type="submit" class="btn btn-block login-button">
            <span class="signin">{{ trans('Acl::auth.login.login') }}</span>
        </button>
        <div class="clearfix"></div>

        <br>
        <p><a class="lost-pass-link" href="{{ route('access.password.request') }}" title="{{ trans('Acl::auth.forgot_password.title') }}">{{ trans('Acl::auth.lost_your_password') }}</a></p>

        {!! apply_filters(BASE_FILTER_AFTER_LOGIN_OR_REGISTER_FORM, null, \Modules\Acl\Entities\User::class) !!}

    {!! Form::close() !!}
@stop
