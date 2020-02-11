<?php

namespace Modules\Base\Supports;

use Assets;
use Illuminate\Support\Arr;
use Throwable;

class Editor
{
    public function __construct()
    {
        add_action(BASE_ACTION_ENQUEUE_SCRIPTS, [$this, 'registerAssets'], 12, 1);
    }

    public function registerAssets()
    {
        Assets::addScriptsDirectly(
            config('Base.general.editor.' .
                setting('rich_editor', config('Base.general.editor.primary')) . '.js')
        )
            ->addScriptsDirectly('vendor/core/js/editor.js');
    }

    /**
     * @param $name
     * @param null $value
     * @param bool $withShortCode
     * @param array $attributes
     * @return string
     * @throws Throwable
     */
    public function render($name, $value = null, $withShortCode = false, array $attributes = [])
    {
        $attributes['class'] = Arr::get($attributes, 'class', '') .
            ' editor-' .
            setting('rich_editor', config('Base.general.editor.primary'));

        $attributes['id'] = Arr::has($attributes, 'id') ? $attributes['id'] : $name;
        $attributes['with-short-code'] = $withShortCode;
        $attributes['rows'] = Arr::get($attributes, 'rows', 4);

        return view('Base::elements.forms.editor', compact('name', 'value', 'attributes'))
            ->render();
    }
}
