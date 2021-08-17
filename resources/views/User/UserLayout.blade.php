@extends('layouts.app')
@canany(['create_user', 'retrieve_user', 'update_user'])
@section('content')
@section('CssSection')
    <link href="{{ asset('css/UserLayout.css') }}" rel="stylesheet">
@endsection
@section('title') User @endsection
@endsection
@endcanany
