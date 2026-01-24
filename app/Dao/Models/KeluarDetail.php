<?php

namespace App\Dao\Models;

use App\Dao\Models\Core\SystemModel;

class KeluarDetail extends SystemModel
{
    protected $perPage = 20;
    protected $table = 'keluar_detail';
    protected $primaryKey = 'keluar_detail_id';

    public $incrementing = true;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'keluar_detail_id',
        'keluar_detail_code_barang',
        'keluar_detail_code_keluar',
        'keluar_detail_qty',
    ];

    public function has_keluar()
    {
        return $this->belongsTo(Keluar::class, 'keluar_detail_code_keluar', 'keluar_code');
    }

    public function has_barang()
    {
        return $this->belongsTo(Barang::class, 'keluar_detail_code_barang', 'barang_code');
    }
}
