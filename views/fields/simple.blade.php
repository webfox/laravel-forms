@if($type === 'textarea')
    <textarea name="{{ $name }}" class="form-control {{ $class ?? '' }}" {!! $extraAttributes  !!}>{{ $value }}</textarea>
    <span class="required"></span>
@else
    <input type="{{ $type }}" name="{{ $name }}" class="form-control {{ $class ?? '' }}" value="{{ $value }}" {!! $extraAttributes  !!}>
    <span class="required"></span>
@endif