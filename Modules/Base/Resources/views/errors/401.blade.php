@extends('Base::errors.master')

@section('content')

    <div style="margin: 50px;">
        <div class="col-md-10">
            <h3>{{ trans('Base::errors.401_title') }}</h3>
            <p>{{ trans('Base::errors.reasons') }}</p>
            <ul>
                {!! trans('Base::errors.401_msg') !!}
            </ul>

            <p>{!! trans('Base::errors.try_again') !!}</p>
        </div>
    </div>

@stop
