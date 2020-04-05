<?php

namespace Webfox\LaravelForms;

use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;

class FormModelStack extends Collection
{
    public function __construct($items = [])
    {
        parent::__construct($items);
    }

    public function current()
    {
        return parent::last();
    }

    protected function modelIdentifier(Model $model = null)
    {
        return $model ? get_class($model) . ' (#' . $model->getKey() . ')' : 'null';
    }


}