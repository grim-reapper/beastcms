<?php

namespace Modules\Seo\Contracts;

interface SeoHelperContract extends RenderableContract
{
    /**
     * Get SeoMeta instance.
     *
     * @return \Modules\Seo\Contracts\SeoMetaContract
     */
    public function meta();

    /**
     * Set SeoMeta instance.
     *
     * @param  \Modules\Seo\Contracts\SeoMetaContract $seoMeta
     *
     * @return self
     */
    public function setSeoMeta(SeoMetaContract $seoMeta);

    /**
     * Get SeoOpenGraph instance.
     *
     * @return \Modules\Seo\Contracts\SeoOpenGraphContract
     */
    public function openGraph();

    /**
     * Get SeoOpenGraph instance.
     *
     * @param  \Modules\Seo\Contracts\SeoOpenGraphContract $seoOpenGraph
     *
     * @return self
     */
    public function setSeoOpenGraph(SeoOpenGraphContract $seoOpenGraph);

    /**
     * Get SeoTwitter instance.
     *
     * @return \Modules\Seo\Contracts\SeoTwitterContract
     */
    public function twitter();

    /**
     * Set SeoTwitter instance.
     *
     * @param  \Modules\Seo\Contracts\SeoTwitterContract $seoTwitter
     *
     * @return self
     */
    public function setSeoTwitter(SeoTwitterContract $seoTwitter);

    /**
     * Set title.
     *
     * @param  string $title
     * @param  string|null $siteName
     * @param  string|null $separator
     *
     * @return self
     */
    public function setTitle($title, $siteName = null, $separator = null);

    /**
     * @return string
     */
    public function getTitle();

    /**
     * Set description.
     *
     * @param  string $description
     *
     * @return self
     */
    public function setDescription($description);
}
