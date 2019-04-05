<?php

namespace Webfox\LaravelForms;


use Illuminate\Support\Arr;
use Illuminate\Support\HtmlString;

class Form
{
    protected $options;

    protected $methodMap = [
        'update'  => 'put',
        'destroy' => 'delete',
        'store'   => 'post',
        'get'     => 'get',
        'post'    => 'post',
    ];

    protected $routeMethod;
    protected $formMethod;
    protected $formAction;

    public function __construct(array $options = null)
    {
        if ($options) {
            $this->options = $options;
            $this->findRouteMethod();
            $this->updateModelStack();
        }
    }

    protected function findRouteMethod()
    {
        $match             = Arr::only($this->options, array_keys($this->methodMap));
        $this->routeMethod = $this->methodMap[key($match)];
        $this->formMethod  = $this->routeMethod === 'get' ? 'get' : 'post';
        $this->formAction  = Arr::first($match);
        throw_unless($this->routeMethod, \Exception::class, 'Missing action method for form');
    }

    protected function updateModelStack()
    {
        if (isset($this->options['model'])) {
            app(FormModelStack::class)->push($this->options['model']);
        }
    }

    public function getAttributes()
    {
        $attributes = $this->options['extra'] ?? [];

        $attributes['method'] = $this->formMethod;
        $attributes['action'] = $this->formAction;

        if ($this->options['files'] ?? false) {
            $attributes['enctype'] = 'multipart/form-data';
        }

        return collect($attributes)->map(function ($value, $key) {
            // 0 => 'data-boolean'
            if (is_numeric($key)) {
                return $value;
            }

            // 'data-boolean' => true
            if (is_bool($value) && $value) {
                return $key;
            }

            // 'data-key' => 'some value'
            return sprintf('%s="%s"', $key, htmlspecialchars($value));
        })->filter()->implode(' ');
    }

    public function open()
    {
        $attributes  = $this->getAttributes();
        $csrfField   = in_array($this->routeMethod, ['get']) ? null : csrf_field();
        $methodField = in_array($this->routeMethod, ['post', 'get']) ? null : method_field($this->routeMethod);

        return new HtmlString("<form {$attributes}>\n{$csrfField}\n{$methodField}");
    }

    public function close()
    {
        app(FormModelStack::class)->pop();
        return new HtmlString('</form>');
    }

}