<?php

namespace Modules\Page\Forms;

use Modules\Base\Enums\BaseStatusEnum;
use Modules\Base\Forms\FormAbstract;
use Modules\Page\Http\Requests\PageRequest;
use Modules\Page\Entities\Page;
use Throwable;

class PageForm extends FormAbstract
{

    /**
     * @var string
     */
    protected $template = 'Base::forms.form-tabs';

    /**
     * @return mixed|void
     * @throws Throwable
     */
    public function buildForm()
    {
        $this
            ->setupModel(new Page)
            ->setValidatorClass(PageRequest::class)
            ->withCustomFields()
            ->add('name', 'text', [
                'label'      => trans('Base::forms.name'),
                'label_attr' => ['class' => 'control-label required'],
                'attr'       => [
                    'placeholder'  => trans('Base::forms.name_placeholder'),
                    'data-counter' => 120,
                ],
            ])
            ->add('description', 'textarea', [
                'label'      => trans('Base::forms.description'),
                'label_attr' => ['class' => 'control-label'],
                'attr'       => [
                    'rows'         => 4,
                    'placeholder'  => trans('Base::forms.description_placeholder'),
                    'data-counter' => 400,
                ],
            ])
            ->add('content', 'editor', [
                'label'      => trans('Base::forms.content'),
                'label_attr' => ['class' => 'control-label required'],
                'attr'       => [
                    'placeholder'     => trans('Base::forms.description_placeholder'),
                    'with-short-code' => true,
                ],
            ])
            ->add('is_featured', 'onOff', [
                'label'         => trans('Base::forms.is_featured'),
                'label_attr'    => ['class' => 'control-label'],
                'default_value' => false,
            ])
            ->add('status', 'customSelect', [
                'label'      => trans('Base::tables.status'),
                'label_attr' => ['class' => 'control-label required'],
                'choices'    => BaseStatusEnum::labels(),
            ])
            ->add('template', 'customSelect', [
                'label'      => trans('Base::forms.template'),
                'label_attr' => ['class' => 'control-label required'],
                'choices'    => get_page_templates(),
            ])
            ->add('image', 'mediaImage', [
                'label'      => trans('Base::forms.image'),
                'label_attr' => ['class' => 'control-label'],
            ])
            ->setBreakFieldPoint('status');
    }
}
