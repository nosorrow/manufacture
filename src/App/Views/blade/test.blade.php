@extends('blade.layout.head')

@section('title', 'Page Title')

@section('content')
    <h1>proba</h1>
    <?php $obj = new DateTime() ;?>
    @datetime($obj)
@endsection
