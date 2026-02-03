@extends('layouts.print')

@section('header')

@endsection

@section('content')

<style>
     @page { margin: 10px; }
        body { margin: 0px; text-align: center; }
</style>

<div>

    <!-- HEADER -->
    <div style="text-align: center; margin: 0px;margin-top:10px">
        <p style="margin: 0px;padding:0px;">
            {{ $model->field_primary ?? '' }}
        </p>

        <p style="margin-top: 5px;margin-bottom:10px;padding:0px;">
             <img src="data:image/svg+xml;base64,{{ $qrcode }}" width="50" height="50">
        </p>

        <p style="margin: 0px;padding:0px;font-size:14px;">
            {{ $model->field_name ?? '' }}
        </p>

    </div>

</div>
@endsection