@extends('layouts.print')

@section('header')

<x-action_print/>

@endsection

@section('content')
<div class="invoice">

    <!-- HEADER -->
    <div class="space text-center">
        <h2>
            {{ $model->field_primary ?? '' }}
        </h2>

        <h4 class="text-center">
            {!! QrCode::size(100)->generate($model->field_primary) !!}
        </h4>

        <h3 class="text-center">
            {{ $model->field_name ?? '' }}
        </h3>

    </div>

</div>
@endsection