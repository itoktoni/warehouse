<div>
    <div class="card">
        <div class="card-header">
            <div class="card-title">
                @if (request()->segment(5) == 'update')
                    Update Transaksi Keluar
                @else
                    Buat Transaksi Keluar
                @endif
            </div>
        </div>

        <div class="card-body">
            <div class="row">

                @if ($form)
                    <div class="col-md-3 mb-3">
                        <label for="keluar_code" class="form-label">Generated Code</label>
                        <input type="text" class="form-control" id="keluar_code" wire:model="keluar_code" readonly>
                    </div>
                @endif

                <div class="col-md-3 mb-3">
                    <label for="keluar_tanggal" class="form-label">Tanggal</label>
                    <input type="date" class="form-control" id="keluar_tanggal" wire:model="keluar_tanggal">
                    @error('keluar_tanggal')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-3 mb-3">
                    <label for="keluar_id_departemen" class="form-label">Departemen</label>
                    <select class="form-control" id="keluar_id_departemen" wire:model="keluar_id_departemen">
                        <option value="">Select Departemen</option>
                        @foreach ($departemen as $key => $value)
                            <option value="{{ $key }}">{{ $value }}</option>
                        @endforeach
                    </select>
                    @error('keluar_id_departemen')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-3 mb-3">
                    <label for="keluar_nama" class="form-label">Nama Penerima</label>
                    <input type="text" class="form-control" id="keluar_nama" wire:model="keluar_nama">
                    @error('keluar_nama')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-12 mb-12">
                    <label for="keluar_catatan" class="form-label">Catatan</label>
                    <textarea class="form-control" id="keluar_catatan" wire:model="keluar_catatan" rows="3"></textarea>
                </div>
            </div>

            @if (empty($keluar_code))
                <!-- Barcode Scanner Input -->
                <div class="row mt-3">
                    <div class="col-md-6">
                        <label for="barcode_input" class="form-label">Scan Barcode</label>
                        <div class="input-group">
                            <input type="text" id="barcode_input" class="form-control" wire:model="barcode_input"
                                wire:keydown.enter.prevent="addScannedItem" placeholder="Scan barcode here..."
                                autocomplete="off">
                            <button type="button" class="btn btn-outline-secondary" wire:click="addScannedItem">
                                Add
                            </button>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Scanned Items Table -->
            <div class="row mt-3">
                <div class="col-md-12">
                    <h5>Scanned Items</h5>
                    @error('scanned_items')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                    @if (session()->has('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif
                    @if (session()->has('message'))
                        <div class="alert alert-success">
                            {{ session('message') }}
                        </div>
                    @endif
                    <div class="table-responsive">
                        <table class="table table-bordered" id="scanned_items_table">
                            <thead>
                                <tr>
                                    <th>Barcode</th>
                                    <th>Item Name</th>
                                    <th>Quantity</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody id="scanned_items_body">
                                @forelse($scanned_items as $index => $item)
                                    <tr>
                                        <td>{{ $item['barang_code'] }}</td>
                                        <td>{{ $item['barang_nama'] }}</td>
                                        <td>
                                            <input type="number" class="form-control focus"
                                                @if ($item['db']) readonly @endif
                                                wire:model.live="scanned_items.{{ $index }}.qty"
                                                wire:change="updateQuantity({{ $index }}, $event.target.value)"
                                                min="1" style="width: 80px;">
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-danger btn-sm"
                                                wire:click="removeScannedItem({{ $index }}, {{ $item['db'] ?? null }})">
                                                Hapus
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center text-muted">
                                            No items scanned yet
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="page-action">
                <h5 class="action-container">
                    <div class="button">
                        <div class="button button-action">
                            <button type="button" class="btn btn-primary" wire:click="save">Save</button>
                        </div>
                    </div>
                </h5>
            </div>
        </div>
    </div>
</div>
