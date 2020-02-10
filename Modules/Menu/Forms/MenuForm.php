<?php

namespace Modules\Menu\Forms;

use Modules\Base\Enums\BaseStatusEnum;
use Modules\Base\Forms\FormAbstract;
use Modules\Menu\Http\Requests\MenuRequest;
use Modules\Menu\Entities\Menu;
use Throwable;

class MenuForm extends FormAbstract
{

    /**
     * @return mixed|void
     * @throws Throwable
     */
    public function buildForm()
    {
        $locations = [];

        if ($this->getModel()) {
            $locations = $this->getModel()->locations()->pluck('location')->all();
        }

        $this
            ->setupModel(new Menu)
            ->setFormOption('class', 'form-save-menu')
            ->withCustomFields()
            ->setValidatorClass(MenuRequest::class)
            ->add('name', 'text', [
                'label'      => trans('Base::forms.name'),
                'label_attr' => ['class' => 'control-label required'],
                'attr'       => [
                    'placeholder'  => trans('Base::forms.name_placeholder'),
                    'data-counter' => 120,
                ],
            ])
            ->add('status', 'customSelect', [
                'label'      => trans('Base::tables.status'),
                'label_attr' => ['class' => 'control-label required'],
                'choices'    => BaseStatusEnum::labels(),
            ])
            ->addMetaBoxes([
                'structure' => [
                    'wrap'    => false,
                    'content' => view('Menu::menu-structure', [
                        'menu'      => $this->getModel(),
                        'locations' => $locations,
                    ])->render(),
                ],
            ])
            ->setBreakFieldPoint('status');
    }
}
