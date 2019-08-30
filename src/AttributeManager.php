<?php

namespace Webfox\LaravelForms;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\View\View;

class AttributeManager
{

  /** @var \Illuminate\Database\Eloquent\Model|null */
  protected $formModel;

  public function __construct(FormModelStack $modelStack)
  {
    if ($modelStack->isNotEmpty()) {
      $this->setModel($modelStack->last());
    }
  }

  public function setModel(Model $model = null)
  {
    $this->formModel = $model;
    return $this;
  }

  public function getFieldActualName(View $view)
  {
    $extra = $view->offsetExists('extra') ? $view->offsetGet('extra') : [];
    $name  = $view->offsetGet('name');

    foreach ($extra as $key => $value) {
      if ($key === 'multiple' && $value === true) {
        return "{$name}[]";
      }

      if ($value === 'multiple' && (is_int($key) || is_string($key) && ctype_digit($key))) {
        return "{$name}[]";
      }
    }

    return $name;
  }

  public function getFieldOptions(View $view)
  {
    if (!$view->offsetExists('options')) return null;

    $options = $view->offsetGet('options');

    if ($options instanceof \Illuminate\Support\Collection) {
      $options = $options->toArray();
    }

    return array_filter($options, function($value) {
      return !empty($value);
    });
  }

  public function getExtraAttributes(View $view)
  {

    $extra = $view->offsetExists('extra') ? $view->offsetGet('extra') : [];
    if (($extra['data-allow-clear'] ?? false) || in_array('data-allow-clear', $extra)) {
      $view->offsetSet('allowClear', true);
    }

    if ($view->offsetExists('placeholder')) {
      $extra['placeholder'] = $view->offsetGet('placeholder');
    }

    // Extra property mappings for textarea
    if ($view->offsetExists('rows')) {
      $extra['rows'] = $view->offsetGet('rows');
    }

    $valueRequiredBooleans = ['data-allow-clear'];

    // Convert extra properties to key="value" string
    $attributes = array_map(function ($key, $value) use ($valueRequiredBooleans) {
      // ['required']
      if (is_int($key) || is_string($key) && ctype_digit($key)) {
        return in_array($value, $valueRequiredBooleans) ? "{$value}=\"1\"" : $value;
      }

      // ['required' => true]
      if (is_bool($value) && $value) {
        return in_array($key, $valueRequiredBooleans) ? "{$key}=\"1\"" : $key;
      }

      // ['required' => false]
      if (is_bool($value) && !$value) {
        return null;
      }

      return sprintf('%s="%s"', $key, htmlspecialchars($value));
    }, array_keys($extra), $extra);

    // Remove empty (boolean false) attributes
    $attributes = array_filter($attributes);

    return implode(' ', $attributes);
  }

  public function getFieldTemplate(View $view)
  {
    // Check the template
    $fieldType = $this->getFieldType($view);

    $templates = [
      config('forms.field_path') . ".{$fieldType}",
      config('forms.field_path') . ".simple",
    ];

    $template = Arr::first($templates, function ($view) {
      return view()->exists($view);
    });

    if (!$template) {
      throw new \Exception("Unable to locate formField " . implode(', ', $templates));
    }


    return $template;
  }

  public function getFieldType(View $view)
  {
    if ($view->offsetExists('type')) {
      return $view->offsetGet('type');
    }

    if ($view->offsetExists('options')) {
      return 'select';
    }

    return 'text';
  }

  public function getFieldValue(View $view)
  {
    $fieldName = $view->offsetGet('name');
    $fieldType = $this->getFieldType($view);

    // Find the expected value
    if (in_array($fieldType, ['file', 'password'])) {
      return $view->offsetExists('value') ? $view->offsetGet('value') : null;
    }

    $value = null;
    if ($this->formModel && !in_array($fieldName, $this->formModel->getHidden())) {
      $value = $this->formModel->getAttribute($fieldName);
    }
    $camelFieldName = Str::camel($fieldName);
    if ($value === null && $this->formModel && method_exists($this->formModel, $camelFieldName)) {
      $value = $this->formModel->getAttribute($camelFieldName);
    }
    if ($value === null && $view->offsetExists('value')) {
      $value = $view->offsetGet('value');
    }

    $value = old($fieldName, $value);
    
    if (is_object($value) && method_exists($value, 'getValue')) {
      $value = $value->getValue();
    }

    if ($value instanceof Collection) {
      $value = $value->modelKeys();
    }

    return is_bool($value) ? intval($value) : $value;
  }
}
