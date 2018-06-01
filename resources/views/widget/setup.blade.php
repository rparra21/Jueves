@extends('layouts.master')

@section('estilos')
<link href="{{ asset('css/widget.css') }}" rel="stylesheet">

@endsection
@section('title-content','Setup The Articles Widget')
@section('breadcrumb','Setup The Articles Widget')
@section('content')

    <div class="container">
        <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
         
                <label class="control-label" for="checkWidgets">1. Select :</label>

        <div>
        <div class="col-xs-9 col-sm-9 col-md-9 col-lg-9">
                @foreach($categories as $category)
                    <label class="checkbox-inline"><input type="checkbox checkWidgets" id="checkWidgets" name="checkWidgets" value="{{$category->category}}">{{$category->category}}</label>
                @endforeach
        <div>
        <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
         
                <label class="control-label" for="checkWidgets">Pinned:</label>

        <div>
        <div class="col-xs-9 col-sm-9 col-md-9 col-lg-9">
                
                <label class="checkbox-inline"><input type="checkbox" id="checkPinned" name="checkPinned" value="Pinneds">Pinneds</label>
                <label class="checkbox-inline"><input type="checkbox" id="checkAvailable" name="checkAvailable" value="Availables">Availables</label>
                
        <div>

        <input
    </div>
@endsection