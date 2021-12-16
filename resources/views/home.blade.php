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

                    <div class="button-area">
                        <span class="button"><a href="/mypage">マイページ</a></span>
                        <span class="button"><a href="/conference">会議管理</a></span>
                        <span class="button"><a href="/company">会社管理</a></span>
                    </div>
                </div>
            </div>
            <br />
            <div class="card">
                <div class="card-header">本日の内部会議</div>

                <div class="card-body">
                    <table class="meetinglist">
                        <tr>
                            <th>ステータス</th><th>日時</th><th>会議名</th><th>作成者</th><th></th>
                        </tr>
                        @foreach ($innerConfs as $innerConf)
                            <tr>
                                <td>{{ $innerConf['status'] }}</td>
                                <td>{{ $innerConf['schedule'] }}</td>
                                <td>{{ $innerConf['name'] }}</td>
                                <td>{{ $innerConf['username'] }}</td>
                                <td><a href="https://conference.aice.cloud/?secret={{ $innerConf['secret'] }}" target="_blank"><span class="roominbutton">入室する</span></a></td>
                            </tr>
                        @endforeach
                        <!--
                        <tr>
                            <td>営業会議001</td>
                            <td>11/20 10:00</td>
                            <td>開催済み</td>
                            <td>松本</td>
                            <td><a href="https://conference.aice.cloud/?secret=cb78568ce7c60ce76d8b56f39578882e" target="_blank"><span class="roominbutton">入室する</span></a></td>
                        </tr>
                        <tr>
                            <td>営業会議002</td>
                            <td>11/20 10:00</td>
                            <td>開催済み</td>
                            <td>松本</td>
                            <td><a href="https://conference.aice.cloud/?secret=2fb2e9d8df991eb104e92277c438b1d5" target="_blank"><span class="roominbutton">入室する</span></a></td>
                        </tr>
                        <tr>
                            <td>経営会議</td>
                            <td>11/20 10:00</td>
                            <td>開催済み</td>
                            <td>三瓶</td>
                            <td><a href="https://conference.aice.cloud/?secret=92588c4033bbad838fe6c6549362c26a" target="_blank"><span class="roominbutton">入室する</span></a></td>
                        </tr>
                        -->
                    </table>
                </div>
            </div>
            <br />
            <div class="card">
                <div class="card-header">本日の外部会議</div>

                <div class="card-body">
                    <table class="meetinglist">
                        <tr>
                            <th>ステータス</th><th>日時</th><th>会議名</th><th>作成者</th><th></th>
                        </tr>
                        @foreach ($outerConfs as $outerConf)
                            <tr>
                                <td>{{ $outerConf['status'] }}</td>
                                <td>{{ $outerConf['schedule'] }}</td>
                                <td>{{ $outerConf['name'] }}</td>
                                <td>{{ $outerConf['username'] }}</td>
                                <td><a href="https://conference.aice.cloud/?secret={{ $outerConf['secret'] }}" target="_blank"><span class="roominbutton">入室する</span></a></td>
                            </tr>
                        @endforeach
                        <!--
                        <tr>
                            <td>外部会議001</td>
                            <td>11/20 10:00</td>
                            <td>開催済み</td>
                            <td>松本</td>
                            <td><a href="https://conference.aice.cloud/?secret=e991f94c5453936e206dabc3542c3344" target="_blank"><span class="roominbutton">入室する</span></a></td>
                        </tr>
                        <tr>
                            <td>外部会議002</td>
                            <td>11/20 10:00</td>
                            <td>開催済み</td>
                            <td>松本</td>
                            <td><a href="https://conference.aice.cloud/?secret=ba39e17317ea1f80a408e04494acb5f5" target="_blank"><span class="roominbutton">入室する</span></a></td>
                        </tr>
                        -->
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
