@extends(backpack_view('blank'))
@stack('head')

<link rel="stylesheet" href="{{ asset('css/map.css')}}">
 <link rel="stylesheet" href="https://unpkg.com/leaflet@1.8.0/dist/leaflet.css"
   integrity="sha512-hoalWLoI8r4UszCkZ5kL8vayOGVae1oxXe/2A4AO6J9+580uKHDO3JdHb7NzwwzK5xr/Fs0W40kiNHxM9vyTtQ=="
   crossorigin=""/>

@php
@endphp

@section('content')
    <div id="map"></div>
    <input type="hidden" value="{{config('config.url')}}" id="url">
    @if(backpack_user()->id == 1)
        <input type="hidden" value="admin" id="rol">
    @else
        <input type="hidden" value="technician" id="rol">
        <input type="hidden" value="{{backpack_user()->id}}" id="technician_id">
    @endif
 <script src="https://unpkg.com/leaflet@1.8.0/dist/leaflet.js"
   integrity="sha512-BB3hKbKWOc9Ez/TAwyWxNXeoV9c1v6FIeYiBieIWkpLjauysF18NzgR1MBNBXf8/KABdlkX68nAhlwcDFLGPCQ=="
   crossorigin=""></script>
<script src="{{ asset('js/map.js')}}"></script>

@endsection
