@props([
    'fields' => [],
    'model' => null,
    'toggle' => '',
    'hide' => false,
    ])

    @php

        $fields = collect($attributes->get('fields', $fields));

        if(!empty($model))
        {
            $fields = $fields->merge([
                (object)[
                    'code' => $model->field_name(),
                    'name' => 'Name',
                ]
            ]);

            foreach ($model->filterable as $key => $value) {
                $fields = $fields->merge([
                    (object)[
                        'code' => $key,
                        'name' => ucwords(str_replace('_', ' ', $value)),
                    ]
                ]);
            }

        }

        $total = count($fields);
        $col = 'col';

        if($total > 5)
        {
            $col = (12 / $total);
            $hide = boolval($attributes->get('hide', $hide));
        }

        $show = isset($_GET['search']) ? true : false;
        $show_toggle = $show ? 'collapse show' : 'collapse';

        $attributes = $attributes->class([
        'container-fluid filter-container mb-2',
        ])->merge([

        ]);
    @endphp

    <div {{ $attributes }}>
        <div class="row">
            <x-form-select col="6" name="filter" :label=false prepend="Pencarian"
                :options="$fields->pluck('name', 'code')" />
            <x-form-input col="6" placeholder="Pencarian..." :label=false icon="search" toggle="{{ $toggle }}" name="search" />
        </div>
    </div>
