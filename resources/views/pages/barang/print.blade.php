@extends('layouts.print')

@section('header')

@endsection

@section('content')

<style>
     @page { margin: 0px; }
        body { margin: 0px; text-align: center; }
        .container { width: 141.732pt; height: 226.772pt; }
</style>

<div>

    <!-- HEADER -->
    <div style="text-align: center; margin: 10px;">
        <h2 style="margin: 0px;padding:0px;">
            {{ $model->field_primary ?? '' }}
        </h2>

        <h4 style="margin-top: 10px;margin-bottom:10px;padding:0px;">
             <img src="data:image/svg+xml;base64,{{ $qrcode }}" width="70" height="70">
        </h4>

        <h3 style="margin: 0px;padding:0px;">
            {{ $model->field_name ?? '' }}
        </h3>

    </div>

</div>
@endsection