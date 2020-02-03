<?php
if (!function_exists('base_module_path')) {
    /**
     * @return string
     */
    function base_module_path($path = null): string
    {
        return base_path('Modules', $path);
    }
}
