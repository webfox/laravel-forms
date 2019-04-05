# Laravel Forms

By default the views are setup for beagle
Javascript not included

## Directives


### Form Open
```
@form(['update' => route('some-model.store', [$model]), 'model' => $model, 'files' => true, 'extra' => [...]])
@form(['store' => route('some-model.store'), 'files' => true, 'extra' => [...]])
@form(['delete' => route('some-model.delete'), 'files' => true, 'extra' => [...]])
@form(['get' => route('search.route'), 'extra' => [...]])
```

#### Notes
* Using any of these methods will automatically append the **csrf \_token** and correct **route type \_method** fields.
* If a model is passed via the `'model'` key, We will use it's data to populate the form fields value.
* Anything passed via the `'extra'` array will be appended as attributes to the form.

### Form Close
```
@endform
```

#### Notes
* The form is actually a component, which means you need to close it after you're done.
* This will also unbind the `'model'` so future forms on the same page are not affected.


### Form Field
Now we get into the fun stuff. The minimum required for a form field is a name
```
@formField(['name' => 'first_name'])
```
This will render a `type="text"` form field wrapped in a form group with the label of `First Name` and an appropriate value, Additionally this will handle displaying server validation errors.

```
@formField(['name' => 'name', 'label' => 'Your Name'])
```
This will render as above, however the label will be `Your Name`

```
@formField(['name' => 'name', 'label' => false])
```
This will render as above, however there will be no label

```
@formField(['name' => 'work_email', 'type' => 'email' ])
```
This will render a `type="email"` form field following the previous rules

```
@formField(['name' => 'role', 'options' => ['k' => 'v', 'k2' => 'v2']])
```
This will render a `select` with the given options.
If no value is found for this field we will also prepend an empty `<option>` tag

```
@formField(['name' => 'roles', 'options' => ['k' => 'v', 'k2' => 'v2'], 'extra' => ['multiple']])
```
This will render a `select` with the given options, additionally the name on the select will be `roles[]`

```
@formField(['name' => 'first_name', 'extra' => ['required', 'data-boolean', 'data-something' => 'value']])
```
This will render a field with the following additional attributes `required data-boolean data-something="value"`

## Calculating Values
The following order of precedence is used for calculating values for a form
0. Input matching the `name` via Laravels `old($fieldName)` helper.
0. Attributes from the model provided to `@form`
0. Values passed in directly e.g. `@formField(['name' => 'name', 'value' => 'John'])`.

### Note on _old_ input and multiple forms...
Unfortunately, there's no way to scope Laravels `old($fieldName)` helper, meaning that if one form on a page has old input, 
all fields on the page with the same name will be populated with the old input. 
This is not a limitation of this package, rather it is a limitation of Laravel's old input handling.

## A note about templates, backward compatibility and support
We've open sourced this package so you can use it too, however it is primarily designed for
our inhouse usage, meaning some of the templates have specific rules that require javascript from our templates to run,
additionally we'll be releasing often to add new features and fixes we need, but it may break your work.

Rather than using this package directly, we suggest you fork it and maintain a copy for your organisation.

## IDE Helper

If you don't have them already, add the following to your projects `.idea/blade.xml` file so PHPstorm knows about the directives

```xml
<data directive="@endform" />
<data directive="@form" injection="true" prefix="&lt;?php function x(array $options) {}; x(" suffix="); ?&gt;" />
<data directive="@formField" injection="true" prefix="&lt;?php function x(array $options) {}; x(" suffix="); ?&gt;" />
```