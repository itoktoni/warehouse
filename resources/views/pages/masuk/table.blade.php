<x-layout>

    <x-card class="table-container">

        <div class="col-md-12">

            <x-form method="GET" x-init="" x-target="table" role="search" aria-label="Contacts"
                autocomplete="off" action="{{ moduleRoute('getTable') }}">

                 <div class="container-fluid filter-container mb-2">
                    <div class="row">

                        <x-form-input type="date" col="3" label="Start Date" name="start_date" />
                        <x-form-input type="date" col="3" label="End Date" name="end_date" />
                        <x-form-select col="6" name="masuk_id_supplier" label="Supplier" :options="$supplier" />

                    </div>
                </div>

                <x-filter toggle="Filter" :fields="$fields" />
            </x-form>

            <x-form method="POST" action="{{ moduleRoute('getTable') }}">

                <x-action />

                <div class="container-fluid" id="table">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th width="9" class="center">
                                        <input class="btn-check-d" type="checkbox">
                                    </th>
                                    <th class="text-center column-action">{{ __('Action') }}</th>
                                    <th>Code</th>
                                    <th>Tanggal Masuk</th>
                                    <th>Supplier</th>
                                    <th>No. PO</th>
                                    <th>No. Pengiriman</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($data as $table)
                                    <tr>
                                        <td data-label="Checkbox">
                                            <input type="checkbox" class="checkbox" name="code[]"
                                                value="{{ $table->field_primary }}">
                                        </td>
                                        <td data-label="Action" class="col-md-2 text-center column-action">
                                            <x-crud :model="$table" :action="['blank']">
                                                <x-button module="getUpdate" key="{{ $table->field_primary }}" color="primary" icon="pencil-square" />
                                                <x-button module="getDelete" key="{{ $table->field_primary }}" color="danger" icon="trash3" onclick="return confirm('Apakah anda yakin ingin menghapus ?')" class="button-delete" />
                                                <x-button module="getPrint" label="Print" key="{{ $table->field_primary }}" color="success"/>
                                            </x-crud>
                                        </td>

										<td data-label="Code">{{ $table->masuk_code }}</td>
										<td data-label="Tanggal">{{ formatDate($table->masuk_tanggal) }}</td>
										<td data-label="Supplier">{{ $table->supplier_nama }}</td>
										<td data-label="No. PO">{{ $table->masuk_no_po }}</td>
										<td data-label="No. Pengiriman">{{ $table->masuk_no_pengiriman }}</td>

                                    </tr>
                                @empty
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <x-pagination :data="$data" />
                </div>

            </x-form>

        </div>

    </x-card>

</x-layout>
