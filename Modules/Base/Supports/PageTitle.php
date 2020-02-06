<?php

namespace Modules\Base\Supports;

class PageTitle
{
    /**
     * @var string
     */
    protected $title;

    /**
     * @param $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * @param bool $full
     * @return string
     */
    public function getTitle($full = true)
    {
        if (empty($this->title)) {
            return setting('admin_title', config('Base::general.base_name'));
        }

        if (!$full) {
            return $this->title;
        }

        return $this->title . ' | ' . setting('admin_title', config('Base::general.base_name'));
    }
}
