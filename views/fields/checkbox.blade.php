<div class="custom-control custom-checkbox">
    <input type="checkbox" class="custom-control-input @if($errors->has($name)) is-invalid @endif" name="{{ $name }}" value="{{ $value ?? 1 }}" {{ $value ? 'checked' : ''}} {!! $extraAttributes  !!} id="customCheck{{ $name }}">
    <label class="custom-control-label" for="customCheck{{ $name }}">{!! $label !!}</label>
</div>