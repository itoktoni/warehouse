<x-layout>
    <x-form :model="$model">
        <x-card>
            <x-action form="form" />

            <div class="row">
                @bind($model)

                <x-form-select col="6" name="barang_id_category" :options="$category"/>
                <x-form-input col="6" name="barang_nama" />

                @endbind
            </div>

        </x-card>
    </x-form>
</x-layout>
