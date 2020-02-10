<?php

namespace Modules\Seo\Entities;

use Modules\Seo\Contracts\Entities\MiscTagsContract;

class MiscTags implements MiscTagsContract
{

    /**
     * Current URL.
     *
     * @var string
     */
    protected $currentUrl = '';

    /**
     * Meta collection.
     *
     * @var \Modules\Seo\Contracts\Entities\MetaCollectionContract
     */
    protected $meta;

    /**
     * Make MiscTags instance.
     */
    public function __construct()
    {
        $this->meta = new MetaCollection;
        $this->addCanonical();
        $this->addMany(config('Seo.general.misc.default', []));
    }

    /**
     * Get the current URL.
     *
     * @return string
     */
    public function getUrl()
    {
        return $this->currentUrl;
    }

    /**
     * Set the current URL.
     *
     * @param  string $url
     *
     * @return \Modules\Seo\Entities\MiscTags
     */
    public function setUrl($url)
    {
        $this->currentUrl = $url;
        $this->addCanonical();

        return $this;
    }

    /**
     * Make MiscTags instance.
     *
     * @param  array $defaults
     *
     * @return \Modules\Seo\Entities\MiscTags
     */
    public static function make(array $defaults = [])
    {
        return new self();
    }

    /**
     * Add a meta tag.
     *
     * @param  string $name
     * @param  string $content
     *
     * @return \Modules\Seo\Entities\MiscTags
     */
    public function add($name, $content)
    {
        $this->meta->add(compact('name', 'content'));

        return $this;
    }

    /**
     * Add many meta tags.
     *
     * @param  array $meta
     *
     * @return \Modules\Seo\Entities\MiscTags
     */
    public function addMany(array $meta)
    {
        $this->meta->addMany($meta);

        return $this;
    }

    /**
     * Remove a meta from the meta collection by key.
     *
     * @param  array|string $names
     *
     * @return \Modules\Seo\Entities\MiscTags
     */
    public function remove($names)
    {
        $this->meta->remove($names);

        return $this;
    }

    /**
     * Reset the meta collection.
     *
     * @return \Modules\Seo\Entities\MiscTags
     */
    public function reset()
    {
        $this->meta->reset();

        return $this;
    }

    /**
     * Render the tag.
     *
     * @return string
     */
    public function render()
    {
        return $this->meta->render();
    }

    /**
     * Render the tag.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->render();
    }

    /**
     * Check if has the current URL.
     *
     * @return bool
     */
    protected function hasUrl()
    {
        return !empty($this->getUrl());
    }

    /**
     * Add the canonical link.
     *
     * @return \Modules\Seo\Entities\MiscTags
     */
    protected function addCanonical()
    {
        if ($this->hasUrl()) {
            $this->add('canonical', $this->currentUrl);
        }

        return $this;
    }
}