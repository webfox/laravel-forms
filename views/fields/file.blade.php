<div>
    <input type="{{ $type }}" name="{{ $name }}" id="{{ $name }}File" class="inputfile {{ $class ?? '' }}" value="{{ $value }}" {!! $extraAttributes  !!}>
    <label class="btn-secondary btn-block" for="{{ $name }}File">
        <i class="zmdi zmdi-upload"></i>
        @if(isset($model) && $model->hasMedia($name))
            <span>
                    {{ $model->firstMedia($name)->basename }} (Click to change)
             </span>
        @else
            <span>{{ $placeholder ?? 'Select File...' }}</span>
        @endif
    </label>
    <span class="required"></span>
</div>
