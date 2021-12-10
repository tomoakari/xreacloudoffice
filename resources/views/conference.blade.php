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
                <div class="card-header">
                    内部会議一覧
                    <span class="createbutton" @click="test">会議を新たに作成</span>
                </div>

                <div class="card-body">
                    <table class="meetinglist">
                        <tr>
                            <th>会議名</th><th>日時</th><th>ステータス</th><th>作成者</th><th></th>
                        </tr>
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
                    </table>
                </div>
            </div>
            <br />

            <div class="card">
                <div class="card-header">
                    外部会議一覧
                    <span class="createbutton">会議を新たに作成</span>
                </div>

                <div class="card-body">
                    <table class="meetinglist">
                        <tr>
                            <th>会議名</th><th>日時</th><th>ステータス</th><th>作成者</th><th></th>
                        </tr>
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
                    </table>
                </div>
            </div>

        </div>
    </div>
    <script>
        new Vue({
            el: '#app',
            data: {

            },
            methods: {
                test: function(){
                    alert("fff")
                }
            }
        })
    </script>
</div>
@endsection
