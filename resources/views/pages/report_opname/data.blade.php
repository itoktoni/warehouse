<table border="0" class="header">
	<tr>
		<td></td>
		<td colspan="6">
			<h3>
				<b>Report Data Opname</b>
			</h3>
		</td>
		<td rowspan="3">
			<x-logo/>
		</td>
	</tr>
	<tr>
		<td></td>
		<td colspan="10">
			<h3>
				Tanggal Mulai : {{ formatDate($model->opname_mulai) }} - {{ formatDate($model->opname_selesai) }}
			</h3>
		</td>
	</tr>
</table>

<div class="table-responsive" id="table_data">
	<table id="export" border="1" style="border-collapse: collapse !important; border-spacing: 0 !important;"
		class="table table-bordered table-striped table-responsive-stack">
		<thead>
			<tr>
				<th width="1">No. </th>
				<th>KODE BARANG</th>
				<th>NAMA BARANG</th>
				<th>REGISTER</th>
				<th>OPNAME</th>
				<th>SELISIH</th>
			</tr>
		</thead>
		<tbody>
			@php
			$total_berat = 0;
			@endphp

			@forelse($data as $table)
			<tr>
				<td>{{ $loop->iteration }}</td>
				<td>{{ $table->odetail_code_barang }}</td>
				<td>{{ $table->odetail_nama_barang }}</td>
				<td>{{ $table->odetail_register }}</td>
				<td>{{ $table->odetail_opname }}</td>
				<td>{{ $table->odetail_register - $table->odetail_opname }}</td>
			</tr>
			@empty
			@endforelse

		</tbody>
	</table>
</div>

<table class="footer">
	<tr>
		<td colspan="2" class="print-date">{{ env('APP_LOCATION') }}, {{ date('d F Y') }}</td>
	</tr>
	<tr>
		<td colspan="2" class="print-person">{{ auth()->user()->name ?? '' }}</td>
	</tr>
</table>