@extends('layouts.app')

@section('title', 'lab5')
@section('content')
    <a href="{{ url($filePath) }}" class="btn bi-cloud-download" download>
        Скачать Excel
    </a>
@endsection