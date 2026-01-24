<x-layout>
    <x-form :model="$model" action="{{ moduleRoute('postOpname', $model->opname_id) }}" method="POST">
        <x-card label="Opname">
            <x-action form="form" >
                <x-button module="getPrint" label="Print" key="{{ $model->field_primary }}" color="success"/>
            </x-action>

                @bind($model)

                <x-form-input col="4" id="tanggal" name="tanggal" value="{{ formatDate($model->opname_mulai).' - '.formatDate($model->opname_selesai) }}" label="Tanggal Opname"/>

                <div class=" form-group col-md-4 ">
                    <label for="auto_id_filter">Filter Barang</label>
                    <input class="form-control search" type="text" value="" name="filter" id="auto_id_filter">
                </div>

                <div class="container mt-3">

                    <table class="table">
                        <thead>
                            <tr>
                                <th style="width: 5%" class="checkbox-column">No.</th>
                                <th style="width: 60%">Barang</th>
                                <th style="width: 10%" class="text-center">Register</th>
                                <th style="width: 15%" class="text-center">Opname</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($detail as $item)

                                <tr>
                                    <input type="hidden" name="qty[{{ $item->odetail_id ?? null }}][id]"
                                        value="{{ $item->odetail_id ?? null }}" />

                                    <td data-label="No." class="text-center">{{ $loop->iteration }}</td>
                                    <td data-label="Barang">{{ $item->odetail_nama_barang }}</td>

                                    <td class="text-center" data-label="Kotor">
                                        <input type="hidden" name="qty[{{ $item->odetail_id ?? null }}][register]"
                                            value="{{ $item->odetail_register ?? null }}" />

                                        {{ $item->odetail_register }}
                                    </td>
                                    <td data-label="Opname">
                                        <input type="text" name="qty[{{ $item->odetail_id ?? null }}][ketemu]" value="{{ $item->odetail_ketemu ?? null }}"
                                            class="form-control text-center" />
                                </tr>
                            @endforeach
                            <tr>
                                <td class="text-center">*</td>
                                <td>Total Summary Opname</td>
                                <td class="text-center">{{ $detail->sum('odetail_register') }}</td>
                                <td>
                                    <input type="text" value="{{ $detail->sum('odetail_ketemu') }}" class="form-control text-center"/>
                                </td>
                            </tr>
                        </tbody>
                    </table>

                </div>

                @endbind

        </x-card>
    </x-form>

    <x-scriptcustomertable />

</x-layout>
