@php
    $theme = $component->getTheme();
@endphp
@if ($theme === 'tailwind')
    <div class="rounded-md shadow-sm"
        x-data='{
            extraClass: $store.darkMode.on ? "dark" : "",
            init() {
                window.flatpickr($refs.input, {
                    mode:"range",
                    dateFormat: "Y-m-d",
                    enableTime: false,
                    altInput: true,
                    locale: "{{ App::currentLocale() }}",
                    @if ($filter->hasConfig('altFormat')) altFormat:"{{ $filter->getConfig('altFormat') }}", @else altFormat:"F j, Y", @endif
                    @if ($filter->hasConfig('minDate')) minDate:"{{ $filter->getConfig('minDate') }}", @endif
                    @if ($filter->hasConfig('maxDate')) maxDate:"{{ $filter->getConfig('maxDate') }}", @endif
                    defaultDate:[$refs.input.value.split(" ")[0],$refs.input.value.split(" ")[2]],
                });
            }
        }' x-effect="init" placeholder="{{ __('app.enter') }} {{ __('app.date') }}">


        <input type="text" x-ref="input"
            wire:model.debounce.1500ms="{{ $component->getTableName() }}.filters.{{ $filter->getKey() }}"
            wire:key="{{ $component->getTableName() }}-filter-{{ $filter->getKey() }}"
            id="{{ $component->getTableName() }}-filter-{{ $filter->getKey() }}"
            class="px-3 py-2 leading-tight text-gray-700 border rounded shadow appearance-none dark:bg-darker dark:text-white focus:outline-none focus:shadow-outline" />
    </div>
@elseif ($theme === 'bootstrap-4' || $theme === 'bootstrap-5')
    <div class="mb-3 mb-md-0 input-group">
        <input wire:model.stop="{{ $component->getTableName() }}.filters.{{ $filter->getKey() }}"
            wire:key="{{ $component->getTableName() }}-filter-{{ $filter->getKey() }}"
            id="{{ $component->getTableName() }}-filter-{{ $filter->getKey() }}" type="date"
            @if ($filter->hasConfig('min')) min="{{ $filter->getConfig('min') }}" @endif
            @if ($filter->hasConfig('max')) max="{{ $filter->getConfig('max') }}" @endif class="form-control" />
    </div>
@endif
