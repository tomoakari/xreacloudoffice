@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">ダッシュボード</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <ul>
                        <li><a href="/mypage">マイページ</a></li>
                        <li><a href="/conference">会議管理</a></li>
                        <li><a href="/company">会社管理</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
