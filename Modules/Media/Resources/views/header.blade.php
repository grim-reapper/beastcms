<meta name="csrf-token" content="{{ csrf_token() }}">

@foreach(config('Media.media.libraries.stylesheets', []) as $css)
    <link href="{{ url($css) }}" rel="stylesheet" type="text/css"/>
@endforeach

@include('Media::config')
