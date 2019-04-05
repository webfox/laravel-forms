<?php

namespace Webfox\LaravelForms;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class FormModelStack extends Collection
{
    public function __construct($items = [])
    {
        parent::__construct($items);
    }

    public function push($value)
    {
        return parent::push($value);
    }

    public function pop()
    {
        $popped = parent::pop();
        return $popped;
    }

    public function current() {
      return parent::last();
    }

    protected function modelIdentifier(Model $model = null) {
        return $model ? get_class($model) . ' (#' . $model->getKey() .')' : 'null';
    }


}