@if($type === 'textarea')
    <textarea name="{{ $name }}" class="form-control-plaintext {{ $fieldClasses ?? '' }}" readonly {!! $extraAttributes  !!}>{{ $value }}</textarea>
@else
    <input type="text" name="{{ $name }}" class="form-control-plaintext {{ $fieldClasses ?? '' }}" readonly value="{{ $value }}" {!! $extraAttributes  !!}>
@endif