<div class="input-group">
    <div class="input-group-prepend">
        <span class="input-group-text">
            <i class="icon-th icon-calendar"></i>
        </span>
    </div>
    <input type="text" name="{{ $name }}" class="form-control form-control-sm datetimepicker {{ $fieldClasses ?? '' }}"
           placeholder="{{ $placeholder ?? 'Select date...' }}"
           value="{{ $value }}" {!! $extraAttributes  !!}>
</div>