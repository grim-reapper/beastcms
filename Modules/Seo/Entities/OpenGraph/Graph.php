<?php

namespace Modules\Seo\Entities\OpenGraph;

use Modules\Seo\Contracts\Entities\OpenGraphContract;

class Graph implements OpenGraphContract
{
    /**
     * The Open Graph meta collection.
     *
     * @var \Modules\Seo\Contracts\Entities\MetaCollectionContract
     */
    protected $meta;

    /**
     * Make Graph instance.
     */
    public function __construct()
    {
        $this->meta = new MetaCollection;
    }

    /**
     * Set the open graph prefix.
     *
     * @param  string $prefix
     *
     * @return \Modules\Seo\Entities\OpenGraph\Graph
     */
    public function setPrefix($prefix)
    {
        $this->meta->setPrefix($prefix);

        return $this;
    }

    /**
     * Set type property.
     *
     * @param  string $type
     *
     * @return \Modules\Seo\Entities\OpenGraph\Graph
     */
    public function setType($type)
    {
        return $this->addProperty('type', $type);
    }

    /**
     * Set title property.
     *
     * @param  string $title
     *
     * @return \Modules\Seo\Entities\OpenGraph\Graph
     */
    public function setTitle($title)
    {
        return $this->addProperty('title', $title);
    }

    /**
     * Set description property.
     *
     * @param  string $description
     *
     * @return \Modules\Seo\Entities\OpenGraph\Graph
     */
    public function setDescription($description)
    {
        return $this->addProperty('description', $description);
    }

    /**
     * Set url property.
     *
     * @param  string $url
     *
     * @return \Modules\Seo\Entities\OpenGraph\Graph
     */
    public function setUrl($url)
    {
        return $this->addProperty('url', $url);
    }

    /**
     * Set image property.
     *
     * @param  string $image
     *
     * @return \Modules\Seo\Entities\OpenGraph\Graph
     */
    public function setImage($image)
    {
        return $this->addProperty('image', $image);
    }

    /**
     * Set site name property.
     *
     * @param  string $siteName
     *
     * @return \Modules\Seo\Entities\OpenGraph\Graph
     */
    public function setSiteName($siteName)
    {
        return $this->addProperty('site_name', $siteName);
    }

    /**
     * Add many open graph properties.
     *
     * @param  array $properties
     *
     * @return \Modules\Seo\Entities\OpenGraph\Graph
     */
    public function addProperties(array $properties)
    {
        $this->meta->addMany($properties);

        return $this;
    }

    /**
     * Add an open graph property.
     *
     * @param  string $name
     * @param  string $content
     *
     * @return \Modules\Seo\Entities\OpenGraph\Graph
     */
    public function addProperty($name, $content)
    {
        $this->meta->add(compact('name', 'content'));

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
}
