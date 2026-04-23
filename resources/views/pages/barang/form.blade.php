<x-layout>
    <x-form :model="$model">
        <x-card>
            <x-action form="form" />

            <div class="row">
                @bind($model)

                <div class=" form-group col-md-3 ">
                    <label for="auto_id_barang_code">Kode Barang</label>
                    <input class="form-control col-md-3" {{ request()->segment(5) === 'update' ? 'readonly' : '' }} type="text" value="{{ $model->barang_code ?? null }}" name="barang_code">
                </div>

                <x-form-select col="3" name="barang_id_category" label="Kategori" :options="$category"/>
                <x-form-input col="4" name="barang_nama" />

                 <div class="col-md-2">

                    <h6 class="text-center">{{ $model->field_primary }}</h6>

                    @if ($model->field_primary)
                        {!! QrCode::size(200)->generate($model->field_primary) !!}
                    @endif

                </div>

                @endbind
            </div>

        </x-card>
    </x-form>
</x-layout>
