<x-layout>
    <x-form :model="$model">
        <x-card>
            <x-action form="form" />

            <div class="row">
                @bind($model)

                <x-form-input col="6" name="departemen_nama" />
                <x-form-input col="3" name="departemen_pic" />
                <x-form-input col="3" name="departemen_telp" />

                @endbind
            </div>

        </x-card>
    </x-form>
</x-layout>
