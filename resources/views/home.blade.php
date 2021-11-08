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
            <br />
            <div class="card">
                <div class="card-header">内部会議一覧</div>

                <div class="card-body">
                    <ul>
                        <li><a href="https://conftest.aice.cloud/?secret=65be2fcd5259eb27d384abdfed254054" target="_blank">営業会議001</a></li>
                        <li><a href="https://conftest.aice.cloud/?secret=cb4801c38edf66d8f94c85d908ee8da9" target="_blank">営業会議002</a></li>
                        <li><a href="https://conftest.aice.cloud/?secret=d8bd8709c851cddce2d9eb731ce76eb6" target="_blank">経営会議</a></li>
                    </ul>
                </div>
            </div>
            <br />
            <div class="card">
                <div class="card-header">外部会議一覧</div>

                <div class="card-body">
                    <ul>
                        <li><a href="https://conftest.aice.cloud/?secret=ebe5e5c3fffd27f6c07a4f5f568b95d3" target="_blank">外部会議001</a></li>
                        <li><a href="https://conftest.aice.cloud/?secret=96d7ab4f8a2a4b7d6a340ac3ac51e6d0" target="_blank">外部会議002</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
