@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-9">

            <entries-index :userid="{{ Auth::user()->id }}"></entries-index>

        </div>
    </div>
</div>
@endsection
