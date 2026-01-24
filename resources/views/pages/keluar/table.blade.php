<x-layout>

    <x-card class="table-container">

        <div class="col-md-12">

            <x-form method="GET" x-init="" x-target="table" role="search" aria-label="Contacts"
                autocomplete="off" action="{{ moduleRoute('getTable') }}">

                 <div class="container-fluid filter-container mb-2">
                    <div class="row">

                        <x-form-input type="date" col="3" label="Start Date" name="start_date" />
                        <x-form-input type="date" col="3" label="End Date" name="end_date" />
                        <x-form-select col="6" name="keluar_id_departemen" label="Departemen" :options="$departemen" />

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
                                    <th>Tanggal Keluar</th>
                                    <th>Departemen</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($data as $table)
                                    <tr>
                                        <td>
                                            <input type="checkbox" class="checkbox" name="code[]"
                                                value="{{ $table->field_primary }}">
                                        </td>
                                        <td class="col-md-2 text-center column-action">
                                            <x-crud :model="$table">
                                                 <x-button module="getPrint" label="Print" key="{{ $table->field_primary }}" color="success"/>
                                            </x-crud>
                                        </td>

										<td >{{ $table->keluar_code }}</td>
										<td >{{ formatDate($table->keluar_tanggal) }}</td>
										<td >{{ $table->departemen_nama }}</td>

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
