<?php


namespace Modules\Base\Foundation;
use Illuminate\Support\Facades\Auth;
use Exception;
use File;
use Eloquent;
use Request;
use Artisan;
use Schema;
class Helper
{
    public static function autoload($directory)
    {
        $helpers = File::glob($directory . '/*.php');
        foreach ($helpers as $helper) {
            File::requireOnce($helper);
        }
    }

    public static function handleViewCount(Eloquent $object, $sessionName)
    {
        if (!array_key_exists($object->id, session()->get($sessionName, []))) {
            try {
                $object->increment('views');
                session()->put($sessionName . '.' . $object->id, time());
                return true;
            } catch (Exception $ex) {
                return false;
            }
        }

        return false;
    }

    public static function formatLog($input, $line = '', $function = '', $class = '')
    {
        return array_merge($input, [
            'user_id'   => Auth::check() ? Auth::user()->getKey() : 'System',
            'ip'        => Request::ip(),
            'line'      => $line,
            'function'  => $function,
            'class'     => $class,
            'userAgent' => Request::header('User-Agent'),
        ]);
    }

    public static function removePluginData($plugin)
    {
        $folders = [
            public_path('vendor/core/plugins/' . $plugin),
            resource_path('assets/plugins/' . $plugin),
            resource_path('views/vendor/plugins/' . $plugin),
            resource_path('lang/vendor/plugins/' . $plugin),
            config_path('plugins/' . $plugin),
        ];

        foreach ($folders as $folder) {
            if (File::isDirectory($folder)) {
                File::deleteDirectory($folder);
            }
        }

        return true;
    }

    public static function executeCommand(string $command, array $parameters = [], $outputBuffer = null)
    {
        if (!function_exists('proc_open')) {
            if (config('app.debug')) {
                throw new Exception('Function proc_close() is disabled. Please contact your hosting provider to enable it.');
            }
            return false;
        }

        return Artisan::call($command, $parameters, $outputBuffer);
    }

    public static function isConnectedDatabase()
    {
        try {
            return Schema::hasTable('settings');
        } catch (Exception $ex) {
            return false;
        }
    }
}
