<?php

namespace Modules\Base\Providers;

use Form;
use Illuminate\Support\ServiceProvider;

class FormServiceProvider extends ServiceProvider
{

    public function boot()
    {
        Form::component('mediaImage', 'Base::elements.forms.image', [
            'name',
            'value'      => null,
            'attributes' => [],
        ]);

        Form::component('modalAction', 'Base::elements.forms.modal', [
            'name',
            'title',
            'type'        => null,
            'content'     => null,
            'action_id'   => null,
            'action_name' => null,
            'modal_size'  => null,
        ]);

        Form::component('helper', 'Base::elements.forms.helper', ['content']);

        Form::component('onOff', 'Base::elements.forms.on-off', [
            'name',
            'value'      => false,
            'attributes' => [],
        ]);

        /**
         * Custom checkbox
         * Every checkbox will not have the same name
         */
        Form::component('customCheckbox', 'Base::elements.custom-checkbox', [
            /**
             * @var array $values
             * @template: [
             *      [string $name, string $value, string $label, bool $selected, bool $disabled],
             *      [string $name, string $value, string $label, bool $selected, bool $disabled],
             *      [string $name, string $value, string $label, bool $selected, bool $disabled],
             * ]
             */
            'values',
        ]);

        /**
         * Custom radio
         * Every radio in list must have the same name
         */
        Form::component('customRadio', 'Base::elements.custom-radio', [
            /**
             * @var string $name
             */
            'name',
            /**
             * @var array $values
             * @template: [
             *      [string $value, string $label, bool $disabled],
             *      [string $value, string $label, bool $disabled],
             *      [string $value, string $label, bool $disabled],
             * ]
             */
            'values',
            /**
             * @var string|null $selected
             */
            'selected' => null,
        ]);

        Form::component('error', 'Base::elements.forms.error', [
            'name',
            'errors' => null,
        ]);

        Form::component('editor', 'Base::elements.forms.editor-input', [
            'name',
            'value'      => null,
            'attributes' => [],
        ]);

        Form::component('customSelect', 'Base::elements.forms.custom-select', [
            'name',
            'list'                => [],
            'selected'            => null,
            'selectAttributes'    => [],
            'optionsAttributes'   => [],
            'optgroupsAttributes' => [],
        ]);

        Form::component('googleFonts', 'Base::elements.forms.google-fonts', [
            'name',
            'selected'          => null,
            'selectAttributes'  => [],
            'optionsAttributes' => [],
        ]);
    }
}
