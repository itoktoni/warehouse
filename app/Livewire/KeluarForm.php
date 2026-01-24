<?php

namespace App\Livewire;

use Livewire\Component;
use App\Dao\Models\Keluar;
use App\Dao\Models\Barang;
use App\Dao\Models\KeluarDetail;
use Illuminate\Support\Facades\DB;

class KeluarForm extends Component
{
    public $model;
    public $departemen;
    public $keluar_code;
    public $keluar_tanggal;
    public $keluar_id_departemen;
    public $keluar_catatan;
    public $form = false;

    // For barcode scanning
    public $barcode_input = '';
    public $scanned_items = [];

    public function mount($model = null)
    {
        $this->model = $model;

        if ($model && request()->segment(5) == 'update') {
            $this->keluar_code = $model->keluar_code;
            $this->keluar_tanggal = $model->keluar_tanggal ?? date('Y-m-d');
            $this->keluar_id_departemen = $model->keluar_id_departemen;
            $this->keluar_catatan = $model->keluar_catatan;
            $this->form = true;

            $data_detail = KeluarDetail::with('has_barang')->where('keluar_detail_code_keluar', $this->keluar_code)->get();

            // Load existing scanned items if editing
            if ($data_detail) {
                foreach ($data_detail as $detail) {
                    $this->scanned_items[] = [
                        'barang_code' => $detail->keluar_detail_code_barang,
                        'barang_nama' => $detail->has_barang ? $detail->has_barang->barang_nama : $detail->keluar_detail_code_barang,
                        'qty' => $detail->keluar_detail_qty,
                        'db' => $detail->keluar_detail_id
                    ];
                }
            }
        } else {
            $this->keluar_tanggal = date('Y-m-d');
        }

        // Load departemen options
        $this->departemen = \App\Dao\Models\Departemen::getOptions();
    }

    public function render()
    {
        return view('livewire.keluar-form');
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
                    'qty' => 1,
                    'db' => false
                ];
            }

            $this->barcode_input = '';
        } else {
            session()->flash('error', 'Item not found with barcode: ' . $this->barcode_input);
        }
    }

    public function removeScannedItem($index, $id = null)
    {
        unset($this->scanned_items[$index]);
        $this->scanned_items = array_values($this->scanned_items); // Re-index array

        if($id) {
            // If the item exists in the database, delete it
            $detail = KeluarDetail::find($id);
            if ($detail) {
                $barang = Barang::where('barang_code', $detail->keluar_detail_code_barang)->first();
                $qty_barang = $barang->barang_qty;
                $total = $qty_barang + $detail->keluar_detail_qty;
                Barang::where('barang_code', $detail->keluar_detail_code_barang)->update([
                    'barang_qty' => $total
                ]);

                $detail->delete();

                $masuk = KeluarDetail::where('keluar_detail_code_keluar', $detail->keluar_detail_code_keluar)->count();
                if ($masuk == 0) {
                   Keluar::where('keluar_code', $detail->keluar_detail_code_keluar)->delete();
                }
            }
        }
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
            'keluar_tanggal' => 'required|date',
            // 'keluar_id_departemen' => 'required',
        ]);

        DB::beginTransaction();

        try {

            if ($this->keluar_code) {
                // Update existing record
                $this->model->update([
                    'keluar_tanggal' => $this->keluar_tanggal,
                    'keluar_id_departemen' => $this->keluar_id_departemen,
                    'keluar_catatan' => $this->keluar_catatan,
                ]);

                // Delete existing masuk detail records
                KeluarDetail::where('keluar_detail_code_keluar', $this->model->keluar_code)->delete();

                // Get the keluar_code for the existing record
                $masukCode = $this->model->keluar_code;
            } else {

                $code = unic(10).date('Ymd');

                // Create new record
                $newKeluar = Keluar::create([
                    'keluar_code' => $code,
                    'keluar_tanggal' => $this->keluar_tanggal,
                    'keluar_id_departemen' => $this->keluar_id_departemen,
                    'keluar_catatan' => $this->keluar_catatan,
                ]);

                // Get the keluar_code for the newly created record
                $masukCode = $newKeluar->keluar_code;
            }

            // Create masuk detail records using the correct keluar_code
            foreach ($this->scanned_items as $item) {
                KeluarDetail::create([
                    'keluar_detail_code_keluar' => $masukCode,
                    'keluar_detail_code_barang' => $item['barang_code'],
                    'keluar_detail_qty' => $item['qty']
                ]);

                $barang = Barang::where('barang_code', $item['barang_code'])->first();
                $qty_barang = $barang->barang_qty;

                $total = $qty_barang - $item['qty'];
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