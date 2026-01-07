<?php

namespace App\Livewire;

use Livewire\Component;
use App\Dao\Models\Masuk;
use App\Dao\Models\Barang;
use App\Dao\Models\MasukDetail;
use Illuminate\Support\Facades\DB;

class MasukForm extends Component
{
    public $model;
    public $supplier;
    public $masuk_code;
    public $masuk_tanggal;
    public $masuk_id_supplier;
    public $masuk_no_po;
    public $masuk_tanggal_pengiriman;
    public $masuk_no_pengiriman;
    public $masuk_catatan;

    // For barcode scanning
    public $barcode_input = '';
    public $scanned_items = [];

    public function mount($model = null)
    {
        $this->model = $model;

        if ($model) {
            $this->masuk_code = $model->masuk_code;
            $this->masuk_tanggal = $model->masuk_tanggal ?? date('Y-m-d');
            $this->masuk_id_supplier = $model->masuk_id_supplier;
            $this->masuk_no_po = $model->masuk_no_po;
            $this->masuk_tanggal_pengiriman = $model->masuk_tanggal_pengiriman;
            $this->masuk_no_pengiriman = $model->masuk_no_pengiriman;
            $this->masuk_catatan = $model->masuk_catatan;

            $data_detail = MasukDetail::with('has_barang')->where('masuk_detail_code_masuk', $this->masuk_code)->get();

            // Load existing scanned items if editing
            if ($data_detail) {
                foreach ($data_detail as $detail) {
                    $this->scanned_items[] = [
                        'barang_code' => $detail->masuk_detail_code_barang,
                        'barang_nama' => $detail->has_barang ? $detail->has_barang->barang_nama : $detail->masuk_detail_code_barang,
                        'qty' => $detail->masuk_detail_qty
                    ];
                }
            }
        } else {
            $this->masuk_tanggal = date('Y-m-d');
        }

        // Load supplier options
        $this->supplier = \App\Dao\Models\Supplier::getOptions();
    }

    public function render()
    {
        return view('livewire.masuk-form');
    }

    public function addScannedItem()
    {
        if (empty($this->barcode_input)) {
            return;
        }

        // Search for the item in the barang table
        $item = Barang::where('barang_code', $this->barcode_input)->first();

        if ($item) {
            // Check if item already exists in scanned items
            $existingIndex = null;
            foreach ($this->scanned_items as $index => $scannedItem) {
                if ($scannedItem['barang_code'] === $item->barang_code) {
                    $existingIndex = $index;
                    break;
                }
            }

            if ($existingIndex !== null) {
                // Increment quantity if item already exists
                $this->scanned_items[$existingIndex]['qty'] += 1;
            } else {
                // Add new item
                $this->scanned_items[] = [
                    'barang_code' => $item->barang_code,
                    'barang_nama' => $item->barang_nama,
                    'qty' => 1
                ];
            }

            $this->barcode_input = '';
        } else {
            session()->flash('error', 'Item not found with barcode: ' . $this->barcode_input);
        }
    }

    public function removeScannedItem($index)
    {
        unset($this->scanned_items[$index]);
        $this->scanned_items = array_values($this->scanned_items); // Re-index array
    }

    public function updateQuantity($index, $value)
    {
        if (isset($this->scanned_items[$index])) {
            $qty = (int) $value;
            if ($qty > 0) {
                $this->scanned_items[$index]['qty'] = $qty;
            } else {
                $this->scanned_items[$index]['qty'] = 1; // Reset to 1 if invalid
            }
        }
    }

    public function save()
    {
        $this->validate([
            'masuk_tanggal' => 'required|date',
            'masuk_id_supplier' => 'required',
        ]);

        DB::beginTransaction();

        try {

            if ($this->masuk_code) {
                // Update existing record
                $this->model->update([
                    'masuk_tanggal' => $this->masuk_tanggal,
                    'masuk_id_supplier' => $this->masuk_id_supplier,
                    'masuk_no_po' => $this->masuk_no_po,
                    'masuk_tanggal_pengiriman' => $this->masuk_tanggal_pengiriman,
                    'masuk_no_pengiriman' => $this->masuk_no_pengiriman,
                    'masuk_catatan' => $this->masuk_catatan,
                ]);

                // Delete existing masuk detail records
                MasukDetail::where('masuk_detail_code_masuk', $this->model->masuk_code)->delete();

                // Get the masuk_code for the existing record
                $masukCode = $this->model->masuk_code;
            } else {

                $code = unic(10).date('Ymd');

                // Create new record
                $newMasuk = Masuk::create([
                    'masuk_code' => $code,
                    'masuk_tanggal' => $this->masuk_tanggal,
                    'masuk_id_supplier' => $this->masuk_id_supplier,
                    'masuk_no_po' => $this->masuk_no_po,
                    'masuk_tanggal_pengiriman' => $this->masuk_tanggal_pengiriman,
                    'masuk_no_pengiriman' => $this->masuk_no_pengiriman,
                    'masuk_catatan' => $this->masuk_catatan,
                ]);

                // Get the masuk_code for the newly created record
                $masukCode = $newMasuk->masuk_code;
            }

            // Create masuk detail records using the correct masuk_code
            foreach ($this->scanned_items as $item) {
                MasukDetail::create([
                    'masuk_detail_code_masuk' => $masukCode,
                    'masuk_detail_code_barang' => $item['barang_code'],
                    'masuk_detail_qty' => $item['qty']
                ]);

                $barang = Barang::where('barang_code', $item['barang_code'])->first();
                $qty_barang = $barang->barang_qty;

                $total = $qty_barang + $item['qty'];
                Barang::where('barang_code', $item['barang_code'])->update([
                    'barang_qty' => $total
                ]);
            }

            DB::commit();

            session()->flash('message', 'Data saved successfully.');
            // Redirect to the table view which seems to be the list page
            return redirect()->back(); // Use the table route as the list page

        } catch (\Exception $e) {
            DB::rollback();
            session()->flash('error', 'Error saving data: ' . $e->getMessage());
        }
    }
}