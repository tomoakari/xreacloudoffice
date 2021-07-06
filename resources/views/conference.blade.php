@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">

            @if (session('status'))
                <div class="alert alert-success" role="alert">
                    {{ session('status') }}
                </div>
            @endif

            <div class="card">
                <div class="card-header">Inhouse Conference</div>

                <div class="card-body">
                    <ul>
                        <li><a href="/conference/inhouse">inhouse</a></li>
                    </ul>
                </div>
            </div>
            <br />

            <div class="card">
                <div class="card-header">Reception Conference</div>

                <div class="card-body">
                    <ul>
                        <li><a href="/conference/reception">Create Reception</a></li>
                    </ul>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
