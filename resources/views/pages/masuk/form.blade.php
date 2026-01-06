<x-layout>
    <x-form :model="$model">
        <x-card>
            <x-action form="form" />

            <div class="row">
                @bind($model)

                <x-form-input col="3" name="masuk_code" label="Generated Code" readonly/>
                <x-form-input col="3" type="date" name="masuk_tanggal" value="{{ $model->masuk_tanggal ?? date('Y-m-d') }}" />

                <x-form-select col="3" name="masuk_id_supplier" :options="$supplier"/>
                <x-form-input col="3" name="masuk_no_po" label="Nomer PO"/>
                <x-form-input col="3" type="date" name="masuk_tanggal_pengiriman" label="Tanggal Pengiriman"/>
                <x-form-input col="3" name="masuk_no_pengiriman" label="Nomer Pengiriman"/>

                <x-form-textarea col="6" name="masuk_catatan" />

                @endbind
            </div>

        </x-card>
    </x-form>
</x-layout>
