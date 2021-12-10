@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">mypage</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <ul>
                        <li>id: {{ $user.id }}</li>
                        <li>※{{ Auth::user()->name }}</li>
                    </ul>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
