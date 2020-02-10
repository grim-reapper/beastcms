<?php

namespace Modules\Widget\Widgets;

use Modules\Widget\AbstractWidget;
use Illuminate\Contracts\Filesystem\FileNotFoundException;

class Text extends AbstractWidget
{
    /**
     * The configuration array.
     *
     * @var array
     */
    protected $config = [];

    /**
     * @var string
     */
    protected $frontendTemplate = 'Widget::widgets.text.frontend';

    /**
     * @var string
     */
    protected $backendTemplate = 'Widget::widgets.text.backend';

    /**
     * @var bool
     */
    protected $isCore = true;

    /**
     * Text constructor.
     *
     * @throws FileNotFoundException
     */
    public function __construct()
    {
        parent::__construct([
            'name'        => trans('Widget::global.widget_text'),
            'description' => trans('Widget::global.widget_text_description'),
            'content'     => null,
        ]);
    }
}
