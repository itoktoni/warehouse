<x-layout>
    <x-form :model="$model">
        <x-card>
            <x-action form="form" />

            <div class="row">
                @bind($model)
                    
                <x-form-input col="6" name="masuk_code" />
                <x-form-input col="6" name="masuk_no_po" />
                <x-form-input col="6" name="masuk_tanggal_pengiriman" />
                <x-form-input col="6" name="masuk_no_pengiriman" />
                <x-form-input col="6" name="masuk_id_supplier" />
                <x-form-input col="6" name="masuk_tanggal" />
                <x-form-input col="6" name="masuk_catatan" />
                <x-form-input col="6" name="masuk_created_at" />
                <x-form-input col="6" name="masuk_updated_at" />
                <x-form-input col="6" name="masuk_created_by" />
                <x-form-input col="6" name="masuk_updated_by" />

                @endbind
            </div>

        </x-card>
    </x-form>
</x-layout>
