<div class="switch-button switch-button-xs switch-button-yesno">
    <input type="checkbox" name="{{ $name }}" value="1" @if($value ?? false) checked @endif {!! $extraAttributes  !!} id="customSwitch{{ $name }}">
    <span><label for="customSwitch{{ $name }}"></label></span>
</div>