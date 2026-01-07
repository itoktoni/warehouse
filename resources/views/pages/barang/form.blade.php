<x-layout>
    <x-form :model="$model">
        <x-card>
            <x-action form="form" />

            <div class="row">
                @bind($model)

                <x-form-select col="4" name="barang_id_category" :options="$category"/>
                <x-form-input col="5" name="barang_nama" />

                 <div class="col-md-2">

                    <h6 class="text-center">{{ $model->field_primary }}</h6>

                    @if ($model)
                        {!! QrCode::size(200)->generate($model->field_primary) !!}
                    @endif

                </div>

                @endbind
            </div>

        </x-card>
    </x-form>
</x-layout>
