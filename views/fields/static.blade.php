@if($type === 'textarea')
    <textarea name="{{ $name }}" class="form-control-plaintext {{ $class ?? '' }}" readonly {!! $extraAttributes  !!}>{{ $value }}</textarea>
@else
    <input type="text" name="{{ $name }}" class="form-control-plaintext {{ $class ?? '' }}" readonly value="{{ $value }}" {!! $extraAttributes  !!}>
@endif