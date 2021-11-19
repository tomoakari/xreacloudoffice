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
                <div class="card-header">会社管理画面</div>

                <div class="card-body">
                    <ul>
                        <li><a href="/company/invite">従業員を作成する</a></li>
                        <li><a href="">edit</a></li>
                        <li><a href="">payment</a></li>
                    </ul>
                </div>
            </div>
            <br />

            <div class="card">
                <div class="card-header">会社新規作成</div>

                <div class="card-body">
                    <ul>
                        <li><a href="/company/organize">organize</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
