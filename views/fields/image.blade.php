<div class="d-flex">
    <div class="flex-grow-1 position-relative">
        <input type="file" name="{{ $name }}" id="{{ $name }}File" class="inputfile {{ $class ?? '' }}" value="{{ $value }}" {!! $extraAttributes  !!}
        accept="image/*">
        <label class="btn-block" for="{{ $name }}File">
            <i class="zmdi zmdi-upload"></i>
            @if(isset($model) && $model->hasMedia($name))
                <span>
                    {{ $model->firstMedia($name)->basename }} (Click to change)
                </span>
            @else
                <span>{{ $placeholder ?? 'Select Image...' }}</span>
            @endif
        </label>
        <span class="required"></span>
    </div>
    @if(isset($model) && isset($preview) && $preview !== false)
        @if($model->hasMedia($name))
            <img src="{{ image($model->firstMedia($name), $preview) }}" class="ml-1 img-thumbnail" alt="Image">
        @endif
    @endif
</div>
