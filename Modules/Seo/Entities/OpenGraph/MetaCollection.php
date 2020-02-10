<?php

namespace Modules\Seo\Entities\OpenGraph;

use Modules\Seo\Bases\MetaCollection as BaseMetaCollection;

class MetaCollection extends BaseMetaCollection
{
    /**
     * Meta tag prefix.
     *
     * @var string
     */
    protected $prefix = 'og:';

    /**
     * Meta tag name property.
     *
     * @var string
     */
    protected $nameProperty = 'property';
}
