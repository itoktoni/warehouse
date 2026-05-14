<x-layout>
    <x-form :model="$model">
        <x-card>
            <x-action form="form" />

            <div class="row">
                @bind($model)

                <x-form-input col="5" readonly name="barang_nama" />
                <x-form-input col="5" value="{{ $model->barang_qty ?? 0 }}" name="barang_qty" />

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
