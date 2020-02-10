<?php

namespace Modules\Slug;

use Illuminate\Support\Arr;

class SlugHelper
{
    /**
     * @param string | array $model
     * @return $this
     */
    public function registerModule($model): self
    {
        if (!is_array($model)) {
            $model = [$model];
        }
        config([
            'Slug.general.supported' => array_merge(config('Slug.general.supported', []), $model),
        ]);

        return $this;
    }

    /**
     * @param string $model
     * @param string|null $prefix
     * @return $this
     */
    public function setPrefix(string $model, ?string $prefix): self
    {
        $prefixes = config('Slug.general.prefixes', []);
        $prefixes[$model] = $prefix;

        config(['Slug.general.prefixes' => $prefixes]);

        return $this;
    }

    /**
     * @param string $model
     * @return string|null
     */
    public function getPrefix(string $model): ?string
    {
        return Arr::get(config('Slug.general.prefixes', []), $model, '');
    }

    /**
     * @return array
     */
    public function supportedModels()
    {
        return config('Slug.general.supported', []);
    }

    /**
     * @return array
     */
    public function isSupportedModel(string $model): bool
    {
        return in_array($model, $this->supportedModels());
    }

    /**
     * @param $model
     * @return $this
     */
    public function disablePreview($model)
    {
        if (!is_array($model)) {
            $model = [$model];
        }
        config([
            'Slug.general.disable_preview' => array_merge(config('Slug.general.disable_preview', []), $model),
        ]);

        return $this;
    }

    public function canPreview(string $model)
    {
        return !in_array($model, config('Slug.general.disable_preview', []));
    }
}
