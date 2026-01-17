<div>
    <div class="card">
        <div class="card-header">
            <div class="card-title">
                @if(request()->segment(5) == 'update')
                    Update Transaksi Masuk
                @else
                    Buat Transaksi Masuk
                @endif
            </div>
        </div>

        <div class="card-body">
            <div class="row">
                <div class="col-md-3 mb-3">
                    <label for="masuk_code" class="form-label">Generated Code</label>
                    <input type="text"
                           class="form-control"
                           id="masuk_code"
                           wire:model="masuk_code"
                           readonly>
                </div>

                <div class="col-md-3 mb-3">
                    <label for="masuk_tanggal" class="form-label">Tanggal</label>
                    <input type="date"
                           class="form-control"
                           id="masuk_tanggal"
                           wire:model="masuk_tanggal">
                </div>

                <div class="col-md-3 mb-3">
                    <label for="masuk_id_supplier" class="form-label">Supplier</label>
                    <select class="form-control"
                            id="masuk_id_supplier"
                            wire:model="masuk_id_supplier">
                        <option value="">Select Supplier</option>
                        @foreach($supplier as $key => $value)
                            <option value="{{ $key }}">{{ $value }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3 mb-3">
                    <label for="masuk_no_po" class="form-label">Nomer PO</label>
                    <input type="text"
                           class="form-control"
                           id="masuk_no_po"
                           wire:model="masuk_no_po">
                </div>

                <div class="col-md-3 mb-3">
                    <label for="masuk_tanggal_pengiriman" class="form-label">Tanggal Pengiriman</label>
                    <input type="date"
                           class="form-control"
                           id="masuk_tanggal_pengiriman"
                           wire:model="masuk_tanggal_pengiriman">
                </div>

                <div class="col-md-3 mb-3">
                    <label for="masuk_no_pengiriman" class="form-label">Nomer Pengiriman</label>
                    <input type="text"
                           class="form-control"
                           id="masuk_no_pengiriman"
                           wire:model="masuk_no_pengiriman">
                </div>

                <div class="col-md-6 mb-3">
                    <label for="masuk_catatan" class="form-label">Catatan</label>
                    <textarea class="form-control"
                              id="masuk_catatan"
                              wire:model="masuk_catatan"
                              rows="3"></textarea>
                </div>
            </div>

            <!-- Barcode Scanner Input -->
            <div class="row mt-3">
                <div class="col-md-6">
                    <label for="barcode_input" class="form-label">Scan Barcode</label>
                    <div class="input-group">
                        <input type="text"
                               id="barcode_input"
                               class="form-control"
                               wire:model="barcode_input"
                               wire:keydown.enter.prevent="addScannedItem"
                               placeholder="Scan barcode here..."
                               autocomplete="off">
                        <button type="button"
                                class="btn btn-outline-secondary"
                                wire:click="addScannedItem">
                            Add
                        </button>
                    </div>
                </div>
            </div>

            <!-- Scanned Items Table -->
            <div class="row mt-3">
                <div class="col-md-12">
                    <h5>Scanned Items</h5>
                    @if(session()->has('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif
                    @if(session()->has('message'))
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
                                            <input type="number"
                                                   class="form-control"
                                                   wire:model.live="scanned_items.{{ $index }}.qty"
                                                   wire:change="updateQuantity({{ $index }}, $event.target.value)"
                                                   min="1"
                                                   style="width: 80px;">
                                        </td>
                                        <td>
                                            <button type="button"
                                                    class="btn btn-danger btn-sm"
                                                    wire:click="removeScannedItem({{ $index }})">
                                                Remove
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

            <div class="d-flex justify-content-end mt-3">
                <button type="button" class="btn btn-primary" wire:click="save">Save</button>
            </div>
        </div>
    </div>
</div>