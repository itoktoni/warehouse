@extends('layouts.print')

@section('header')

<x-action_print/>

@endsection

@section('content')
<div class="invoice">

    <!-- HEADER -->
    <div class="invoice-header">
        <h1>OPNAME</h1>
    </div>

    <!-- CUSTOMER INFO -->
    <div class="invoice-info">
        <p>Tanggal: {{ formatDate($model->opname_mulai ?? '') }} - {{ formatDate($model->opname_selesai ?? '') }}</p>
        <p>Code: {{ $model->field_primary ?? '' }}</p>
    </div>

    <!-- TABLE -->
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th class="col-no">No.</th>
                    <th class="col-name text-left" style="width:70%;">Nama Barang</th>
                    <th class="col-qty" style="width:15%;">Register</th>
                    <th class="col-qty" style="width:15%;">Opname</th>
                </tr>
            </thead>
            <tbody>
                @forelse($detail as $table)
					<tr class="item {{ $loop->last ? 'last' : '' }}">
						<td class="col-no">{{ $loop->iteration }}</td>
						<td class="col-name text-left">{{ $table->odetail_nama_barang ?? '' }}</td>
						<td class="col-qty">{{ $table->odetail_register ?? 0 }}</td>
						<td class="col-qty">{{ $table->odetail_ketemu ?? 0 }}</td>
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
					<td class="col-qty">{{ $detail->sum('odetail_register') }}</td>
					<td class="col-qty">{{ $detail->sum('odetail_ketemu') }}</td>
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