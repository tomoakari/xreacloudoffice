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
                <div class="card-header">本日の内部会議</div>

                <div class="card-body">
                    <ul>
                        <li><a href="https://conference.aice.cloud/?secret=cb78568ce7c60ce76d8b56f39578882e" target="_blank">営業会議001</a></li>
                        <li><a href="https://conference.aice.cloud/?secret=2fb2e9d8df991eb104e92277c438b1d5" target="_blank">営業会議002</a></li>
                        <li><a href="https://conference.aice.cloud/?secret=92588c4033bbad838fe6c6549362c26a" target="_blank">経営会議</a></li>
                    </ul>
                </div>
            </div>
            <br />
            <div class="card">
                <div class="card-header">本日の外部会議</div>

                <div class="card-body">
                    <ul>
                        <li><a href="https://conference.aice.cloud/?secret=e991f94c5453936e206dabc3542c3344" target="_blank">外部会議001</a></li>
                        <li><a href="https://conference.aice.cloud/?secret=ba39e17317ea1f80a408e04494acb5f5" target="_blank">外部会議002</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
