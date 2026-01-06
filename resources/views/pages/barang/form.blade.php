<x-layout>
    <x-form :model="$model">
        <x-card>
            <x-action form="form" />

            <div class="row">
                @bind($model)
                    
                <x-form-input col="6" name="barang_code" />
                <x-form-input col="6" name="barang_nama" />
                <x-form-input col="6" name="barang_id_category" />

                @endbind
            </div>

        </x-card>
    </x-form>
</x-layout>
