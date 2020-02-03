<?php

namespace Modules\Base\Entities;


class MetaBox extends BaseModel
{
    protected $table = 'meta_data';
    protected $cast = ['meta_value' => 'json',];

    public function reference()
    {
        return $this->morphTo();
    }
}
