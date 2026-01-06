<x-layout>
    <x-form :model="$model">
        <x-card>
            <x-action form="form" />

            <div class="row">
                @bind($model)

                <x-form-input col="6" name="supplier_nama" label="Nama Supplier" />
                <x-form-input col="6" name="supplier_pic" label="PIC"/>
                <x-form-input col="3" name="supplier_telp" />
                <x-form-input col="3" name="supplier_email" />
                <x-form-textarea col="6" name="supplier_alamat" />

                @endbind
            </div>

        </x-card>
    </x-form>
</x-layout>
