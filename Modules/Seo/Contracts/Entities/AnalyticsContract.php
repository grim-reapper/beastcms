<?php

namespace Modules\Seo\Contracts\Entities;

use Modules\Seo\Contracts\RenderableContract;

interface AnalyticsContract extends RenderableContract
{
    /**
     * Set Google Analytics code.
     *
     * @param  string $code
     *
     * @return self
     */
    public function setGoogle($code);
}
