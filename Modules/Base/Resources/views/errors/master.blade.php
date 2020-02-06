@extends('Base::layouts.base')

@section ('page')
    <div class="page-wrapper">

        @include('Base::layouts.partials.top-header')
        <div class="clearfix"></div>

        <div class="page-container" style="background: #eef1f5">
            <div class="page-content" style="min-height: calc(100vh - 49px); height: 100%;">
                @yield('content')
            </div>
            <div class="clearfix"></div>
        </div>

        @include('Base::layouts.partials.footer')

    </div>
@stop
