<div>
    <input type="{{ $type }}" name="{{ $name }}" id="{{ $name }}File" class="inputfile {{ $class ?? '' }}" value="{{ $value }}" {!! $extraAttributes  !!}>
    <label class="btn-secondary btn-block" for="{{ $name }}File"><i class="zmdi zmdi-upload"></i><span>{{ $placeholder ?? 'Select File...' }}</span></label>
    <span class="required"></span>
</div>
