@extends('Base::layouts.master')
@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="bs-callout bs-callout-primary">
                <p>{{ trans('Base::system.report_description') }}:</p>
                <button id="btn-report" class="btn btn-info btn-sm">{{ trans('Base::system.get_system_report') }}</button>

                <div id="report-wrapper">
                    <textarea name="txt-report" id="txt-report" class="col-sm-12" rows="10" spellcheck="false" onfocus="this.select()">
                        ### {{ trans('Base::system.system_environment') }}

                        - {{ trans('Base::system.cms_version') }}: {{ get_cms_version() }}
                        - {{ trans('Base::system.framework_version') }}: {{ $systemEnv['version'] }}
                        - {{ trans('Base::system.timezone') }}: {{ $systemEnv['timezone'] }}
                        - {{ trans('Base::system.debug_mode') }}: {!! $systemEnv['debug_mode'] ? '&#10004;' : '&#10008;' !!}
                        - {{ trans('Base::system.storage_dir_writable') }}: {!! $systemEnv['storage_dir_writable'] ? '&#10004;' : '&#10008;' !!}
                        - {{ trans('Base::system.cache_dir_writable') }}: {!! $systemEnv['cache_dir_writable'] ? '&#10004;' : '&#10008;' !!}
                        - {{ trans('Base::system.app_size') }}: {{ $systemEnv['app_size'] }}

                        ### {{ trans('Base::system.server_environment') }}

                        - {{ trans('Base::system.php_version') }}: {{ $serverEnv['version'] }}
                        - {{ trans('Base::system.server_software') }}: {{ $serverEnv['server_software'] }}
                        - {{ trans('Base::system.server_os') }}: {{ $serverEnv['server_os'] }}
                        - {{ trans('Base::system.database') }}: {{ $serverEnv['database_connection_name'] }}
                        - {{ trans('Base::system.ssl_installed') }}: {!! $serverEnv['ssl_installed'] ? '&#10004;' : '&#10008;' !!}
                        - {{ trans('Base::system.cache_driver') }}: {{ $serverEnv['cache_driver'] }}
                        - {{ trans('Base::system.queue_connection') }}: {{ $serverEnv['queue_connection'] }}
                        - {{ trans('Base::system.session_driver') }}: {{ $serverEnv['session_driver'] }}
                        - {{ trans('Base::system.mbstring_ext') }}: {!! $serverEnv['mbstring'] ? '&#10004;' : '&#10008;' !!}
                        - {{ trans('Base::system.openssl_ext') }}: {!! $serverEnv['openssl'] ? '&#10004;' : '&#10008;' !!}
                        - {{ trans('Base::system.pdo_ext') }}: {!! $serverEnv['pdo'] ? '&#10004;' : '&#10008;' !!}
                        - {{ trans('Base::system.curl_ext') }}: {!! $serverEnv['curl'] ? '&#10004;' : '&#10008;' !!}
                        - {{ trans('Base::system.exif_ext') }}: {!! $serverEnv['exif'] ? '&#10004;' : '&#10008;' !!}
                        - {{ trans('Base::system.file_info_ext') }}: {!! $serverEnv['fileinfo'] ? '&#10004;' : '&#10008;' !!}
                        - {{ trans('Base::system.tokenizer_ext') }}: {!! $serverEnv['tokenizer']  ? '&#10004;' : '&#10008;'!!}

                        ### {{ trans('Base::system.installed_packages') }}

                        @foreach($packages as $package)
                            - {{ $package['name'] }} : {{ $package['version'] }}
                        @endforeach
                    </textarea>
                    <button id="copy-report" class="btn btn-info btn-sm">{{ trans('Base::system.copy_report') }}</button>
                </div>
            </div>
        </div>
    </div>

    <div class="row"> <!-- Main Row -->

        <div class="col-sm-8"> <!-- Package & Dependency column -->
            <div class="widget meta-boxes">
                <div class="widget-title">
                    <h4>
                        <span>{{ trans('Base::system.installed_packages') }}</span>
                    </h4>
                </div>
                <div class="widget-body">
                    {!! $infoTable->renderTable() !!}
                </div>
            </div>
        </div> <!-- / Package & Dependency column -->

        <div class="col-sm-4"> <!-- Server Environment column -->
            <div class="widget meta-boxes">
                <div class="widget-title">
                    <h4>
                        <span>{{ trans('Base::system.system_environment') }}</span>
                    </h4>
                </div>

                <ul class="list-group">
                    <li class="list-group-item">{{ trans('Base::system.cms_version') }}: {{ get_cms_version() }}</li>
                    <li class="list-group-item">{{ trans('Base::system.framework_version') }}: {{ $systemEnv['version'] }}</li>
                    <li class="list-group-item">{{ trans('Base::system.timezone') }}: {{ $systemEnv['timezone'] }}</li>
                    <li class="list-group-item">{{ trans('Base::system.debug_mode') }}: {!! $systemEnv['debug_mode'] ? '<span class="fas fa-check"></span>' : '<span class="fas fa-times"></span>' !!}</li>
                    <li class="list-group-item">{{ trans('Base::system.storage_dir_writable') }}: {!! $systemEnv['storage_dir_writable'] ? '<span class="fas fa-check"></span>' : '<span class="fas fa-times"></span>' !!}</li>
                    <li class="list-group-item">{{ trans('Base::system.cache_dir_writable') }}: {!! $systemEnv['cache_dir_writable'] ? '<span class="fas fa-check"></span>' : '<span class="fas fa-times"></span>' !!}</li>
                    <li class="list-group-item">{{ trans('Base::system.app_size') }}: {{ $systemEnv['app_size'] }}</li>
                </ul>
            </div>

            <div class="widget meta-boxes">
                <div class="widget-title">
                    <h4>
                        <span>{{ trans('Base::system.server_environment') }}</span>
                    </h4>
                </div>

                <ul class="list-group">
                    <li class="list-group-item">{{ trans('Base::system.php_version') }}: {{ $serverEnv['version'] }}</li>
                    <li class="list-group-item">{{ trans('Base::system.server_software') }}: {{ $serverEnv['server_software'] }}</li>
                    <li class="list-group-item">{{ trans('Base::system.server_os') }}: {{ $serverEnv['server_os'] }}</li>
                    <li class="list-group-item">{{ trans('Base::system.database') }}: {{ $serverEnv['database_connection_name'] }}</li>
                    <li class="list-group-item">{{ trans('Base::system.ssl_installed') }}: {!! $serverEnv['ssl_installed'] ? '<span class="fas fa-check"></span>' : '<span class="fas fa-times"></span>' !!}</li>
                    <li class="list-group-item">{{ trans('Base::system.cache_driver') }}: {{ $serverEnv['cache_driver'] }}</li>
                    <li class="list-group-item">{{ trans('Base::system.session_driver') }}: {{ $serverEnv['session_driver'] }}</li>
                    <li class="list-group-item">{{ trans('Base::system.queue_connection') }}: {{ $serverEnv['queue_connection'] }}</li>
                    <li class="list-group-item">{{ trans('Base::system.openssl_ext') }}: {!! $serverEnv['openssl'] ? '<span class="fas fa-check"></span>' : '<span class="fas fa-times"></span>' !!}</li>
                    <li class="list-group-item">{{ trans('Base::system.mbstring_ext') }}: {!! $serverEnv['mbstring'] ? '<span class="fas fa-check"></span>' : '<span class="fas fa-times"></span>' !!}</li>
                    <li class="list-group-item">{{ trans('Base::system.pdo_ext') }}: {!! $serverEnv['pdo'] ? '<span class="fas fa-check"></span>' : '<span class="fas fa-times"></span>' !!}</li>
                    <li class="list-group-item">{{ trans('Base::system.curl_ext') }}: {!! $serverEnv['curl'] ? '<span class="fas fa-check"></span>' : '<span class="fas fa-times"></span>' !!}</li>
                    <li class="list-group-item">{{ trans('Base::system.exif_ext') }}: {!! $serverEnv['exif'] ? '<span class="fas fa-check"></span>' : '<span class="fas fa-times"></span>' !!}</li>
                    <li class="list-group-item">{{ trans('Base::system.file_info_ext') }}: {!! $serverEnv['fileinfo'] ? '<span class="fas fa-check"></span>' : '<span class="fas fa-times"></span>' !!}</li>
                    <li class="list-group-item">{{ trans('Base::system.tokenizer_ext') }}: {!! $serverEnv['tokenizer']  ? '<span class="fas fa-check"></span>' : '<span class="fas fa-times"></span>'!!}</li>
                </ul>
            </div>
        </div> <!-- / Server Environment column -->

    </div> <!-- / Main Row -->
@stop
