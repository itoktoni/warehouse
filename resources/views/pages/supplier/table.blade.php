<x-layout>

    <x-card class="table-container">

        <div class="col-md-12">

            <x-form method="GET" x-init="" x-target="table" role="search" aria-label="Contacts"
                autocomplete="off" action="{{ moduleRoute('getTable') }}">
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
                                    <th style="width: 60px">ID</th>
                                    <th>Nama Supplier</th>
                                    <th>PIC</th>
                                    <th>Phone</th>
                                    <th>Email</th>
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
                                            <x-crud :model="$table" />
                                        </td>

										<td data-label="ID">{{ $table->supplier_id }}</td>
										<td data-label="Nama">{{ $table->supplier_nama }}</td>
										<td data-label="PIC">{{ $table->supplier_pic }}</td>
										<td data-label="Telp">{{ $table->supplier_telp }}</td>
										<td data-label="Email">{{ $table->supplier_email }}</td>

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
