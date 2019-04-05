<div>
    <div class="btn-group btn-group-toggle" data-toggle="buttons">
        @foreach($options as $optValue => $optText)
            <label class="btn @if(is_array($value) ? in_array($optValue, $value) : $optValue === $value) active @endif">
                <input type="radio" name="{{ $name }}" id="option1" value="{{ $optValue }}" {!! $extraAttributes  !!}
                @if(is_array($value) ? in_array($optValue, $value) : $optValue === $value) checked @endif> {{ $optText }}
            </label>
        @endforeach
    </div>
</div>