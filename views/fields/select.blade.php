<select name="{{ $actualName }}" class="select2" {!! $extraAttributes  !!}>
    @if(($allowClear ?? false) || $value === null)
        <option></option>@endif
    @foreach($options as $optValue => $optText)
        @if(is_array($optText))
            <optgroup label="{{ $optValue }}">
                @foreach($optText as $optGroupOptValue => $optGroupOptText)
                    @if(is_array($value) ? in_array($optGroupOptValue, $value) : $optGroupOptValue === $value)
                        <option value="{{ $optGroupOptValue }}" selected>{{ $optGroupOptText }}</option>
                    @else
                        <option value="{{ $optGroupOptValue }}">{{ $optGroupOptText }}</option>
                    @endif
                @endforeach
            </optgroup>
        @else
            @if(is_array($value) ? in_array($optValue, $value) : $optValue === $value)
                <option value="{{ $optValue }}" selected>{{ $optText }}</option>
            @else
                <option value="{{ $optValue }}">{{ $optText }}</option>
            @endif
        @endif
    @endforeach
</select>
<span class="required"></span>