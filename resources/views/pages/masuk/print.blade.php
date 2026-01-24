@extends('layouts.print')

@section('header')

<x-action_print/>

@endsection

@section('content')
<div class="invoice">

    <!-- HEADER -->
    <div class="invoice-header">
        <h1>TRANSAKSI MASUK</h1>
    </div>

    <!-- CUSTOMER INFO -->
    <div class="invoice-info">
        <h2>Supplier: {{ $supplier->supplier_nama ?? '' }}</h2>
        <p>Tanggal: {{ formatDate($model->masuk_tanggal ?? '') }}</p>
        <p>Code: {{ $model->field_primary ?? '' }}</p>
    </div>

    <!-- TABLE -->
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th class="col-no">No.</th>
                    <th class="col-name text-left" style="width:70%;">Nama Barang</th>
                    <th class="col-qty" style="width:15%;">Qty</th>
                </tr>
            </thead>
            <tbody>
                @forelse($detail as $table)
					<tr class="item {{ $loop->last ? 'last' : '' }}">
						<td class="col-no">{{ $loop->iteration }}</td>
						<td class="col-name text-left">{{ $table->has_barang->barang_nama ?? '' }}</td>
						<td class="col-qty">{{ $table->masuk_detail_qty ?? 0 }}</td>
					</tr>
				@empty
					<tr class="item last">
						<td colspan="3">No data available</td>
					</tr>
				@endforelse
            </tbody>
			<tfoot>
				<tr>
					<td colspan="2" style="text-align: right">Total</td>
					<td class="col-qty">{{ $detail->sum('masuk_detail_qty') }}</td>
				</tr>
			</tfoot>
        </table>

        <table class="footer">
            <p>
                {{ env('APP_LOCATION') }}, {{ date('d F Y') }}
            </p>
            <br>
            <p>
                {{ auth()->user()->name ?? '' }}
            </p>
            <br>
            <span>.</span>
        </table>

    </div>

</div>
@endsection