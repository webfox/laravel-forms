<?php


namespace Webfox\LaravelForms;

use Illuminate\View\View;
use Illuminate\Http\Request;

class FieldViewComposer
{

  /**
   * @var \Illuminate\Http\Request
   */
  protected $request;

  /** @var \Webfox\LaravelForms\AttributeManager */
  protected $attributeManager;

  public function __construct(Request $request, AttributeManager $attributeManager)
  {
    $this->request = $request;
    $this->attributeManager = $attributeManager;
  }

  public function compose(View $view)
  {
    // Calculate the field type
    $fieldType = $this->attributeManager->getFieldType($view);

    // Repopulate the template
    $view->offsetSet('extraAttributes', $this->attributeManager->getExtraAttributes($view));
    $view->offsetSet('containerAttributes', $this->attributeManager->getContainerAttributes($view));
    $view->offsetSet('containerClasses', $this->attributeManager->getContainerClasses($view));
    $view->offsetSet('labelClasses', $this->attributeManager->getLabelClasses($view));
    $view->offsetSet('fieldClasses', $this->attributeManager->getFieldClasses($view));
    $view->offsetSet('fieldTemplate', $this->attributeManager->getFieldTemplate($view));
    $view->offsetSet('value', $this->attributeManager->getFieldValue($view));
    $view->offsetSet('type', $this->attributeManager->getFieldType($view));
    $view->offsetSet('actualName', $this->attributeManager->getFieldActualName($view));
    $view->offsetSet('options', $this->attributeManager->getFieldOptions($view));
    $view->offsetSet('onlyTemplate', in_array($fieldType, ['checkbox']));
    $view->offsetSet('model', app(FormModelStack::class)->current());
  }


}