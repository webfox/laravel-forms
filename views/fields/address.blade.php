<div data-address-lookup="{{ $name }}" @if(\Arr::get($value, 'manual')) style="display:none;" @endif>
    <input type="text" name="{{ $name }}[display]" class="form-control form-control-sm {{ $class ?? '' }}" value="{{ \Arr::get($value, 'display') }}" data-address-block="{{ $name }}" {!! $extraAttributes  !!}>
    <span class="required"></span>
    <div class="enter-address-manually" style="position: absolute; right: 0;">
        <a href="#" data-enter-address-manually="{{ $name }}">Enter Manually</a>
    </div>
</div>
<input type="checkbox" name="{{ $name }}[manual]" value="1" @if(\Arr::get($value, 'manual'))checked @endif style="display:none;">
<div data-address-manual="{{ $name }}" @unless(\Arr::get($value, 'manual'))style="display:none" @endunless>

    <input type="text" class="form-control form-control-sm"
           placeholder="Street Address" name="{{ $name }}[street_1]" value="{{ \Arr::get($value, 'street_1') }}" />

    <input type="text" class="form-control form-control-sm"
           placeholder="Street2" name="{{ $name }}[street_2]" value="{{ \Arr::get($value, 'street_2') }}" />

    <input type="text" class="form-control form-control-sm"
           placeholder="Suburb" name="{{ $name }}[suburb]" value="{{ \Arr::get($value, 'suburb') }}" />

    <input type="text" class="form-control form-control-sm"
           placeholder="City" name="{{ $name }}[city]" value="{{ \Arr::get($value, 'city') }}" />

    <input type="text" class="form-control form-control-sm"
           placeholder="Region" name="{{ $name }}[region]" value="{{ \Arr::get($value, 'region') }}" />

    <input type="text" class="form-control form-control-sm"
           placeholder="Postal Code" name="{{ $name }}[postal_code]" value="{{ \Arr::get($value, 'postal_code') }}" />

    <select class="form-control form-control-sm" name="{{ $name }}[country]">
        @foreach(config('countries.codes') as $countryCode)
            <option value="{{ $countryCode }}" @if($countryCode === \Arr::get($value, 'country', 'NZ'))selected @endif>
                {{ country_from_code($countryCode) }}
            </option>
        @endforeach
    </select>

</div>
@push('scripts:before')
    @if(!config('services.google.maps.loaded'))
        <script src="https://maps.googleapis.com/maps/api/js?key={{ env('GOOGLE_MAPS_API_KEY') }}&libraries=places"></script>
    @endif
@endpush

@php(config()->set('services.google.maps.loaded', true))

@push('scripts:after')
    <script>
    (function () {
      document.querySelector('[data-enter-address-manually="{{ $name }}"]').addEventListener('click', (e) => {
        console.log('manual triggered', '{{ $name }}');
        e.preventDefault();
        document.querySelector('[name="{{ $name }}[manual]"]').checked = true;
        document.querySelector('[data-address-lookup="{{ $name }}"]').style.display = 'none';
        document.querySelector('[data-address-manual="{{ $name }}"]').style.display = 'block';
        return false;
      });

      const selector = `[data-address-block="{{ $name }}"]`;
      const restrictions = { componentRestrictions: { country: 'nz' }, types: ['address'] };

      new AddressAutocomplete(selector, restrictions, (_, { address_components: components, types }) => {
        console.log(components, types);

        const piece = (type, attr = 'short_name') => (components.find(component => component.types.includes(type)) || {})[attr];
        const set = (type, value) => {
          console.log(`Set '${type}' to '${value || ''}'`);
          document.querySelector(`[name="{{ $name }}[${type}]"]`).value = value || '';
        };

        let valid = true;
        if (!(types.includes('street_address'))) {
          valid = false;
          document.querySelector('[data-address-block="{{ $name }}"]').value = '';
          $.notify({
            title: 'Invalid address selected',
            text: 'The address selected was not a valid street address.',
            image: '<i class="ion ion-ios-alert"></i>',
          }, Object.assign({}, window.notifyDefaults, { className: 'error' }));
        }

        set('street_1', valid ? `${piece('street_number')} ${piece('route', 'long_name')}` : ''); // 2 Bridge St
        set('suburb', valid ? piece('sublocality') : '');                            // Ahuriri
        set('city', valid ? piece('locality') : '');                                 // Napier
        set('region', valid ? piece('administrative_area_level_1') : '');            // Hawke's bay
        set('postal_code', valid ? piece('postal_code') : '');                       // 4110
        set('country', valid ? piece('country') : '');                               // NZ
      });
    })();
    </script>
@endpush