@extends('layouts/contentNavbarLayout')

@section('content')
<h2>Preview Laporan Laptop</h2>

<iframe src="{{ route('laporan.previewPDF', request()->all()) }}" style="width:100%; height:600px;"></iframe>

<div style="margin-top:20px;">
    <a href="{{ route('laporan.export', array_merge(request()->all(), ['format' => 'pdf'])) }}" class="btn btn-primary" target="_blank">
        Download PDF
    </a>
</div>
@endsection
