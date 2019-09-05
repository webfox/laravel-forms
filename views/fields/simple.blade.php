@if($type === 'textarea')
    <textarea name="{{ $name }}" class="form-control {{ $fieldClasses ?? '' }}" {!! $extraAttributes  !!}>{{ $value }}</textarea>
    <span class="required"></span>
@else
    <input type="{{ $type }}" name="{{ $name }}" class="form-control {{ $fieldClasses ?? '' }}" value="{{ $value }}" {!! $extraAttributes  !!}>
    <span class="required"></span>
@endif