@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center" id="vueapp">
        <div class="col-md-8">

            @if (session('status'))
                <div class="alert alert-success" role="alert">
                    {{ session('status') }}
                </div>
            @endif

            <div class="card">
                <div class="card-header">
                    内部会議一覧
                    <span class="createbutton" v-click="test">会議を新たに作成</span>
                </div>

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
                    </table>
                </div>
            </div>

        </div>
    </div>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/vue@2.6.14"></script>
    <script>
        function addConf(){
            alert("addConf()");
        }
        new Vue({
            el: '#vueapp',
            data: {
                message: "hello",
            },
            methods: {
                test: function(){
                    Swal.fire({
  title: 'Error!',
  text: 'Do you want to continue',
  icon: 'error',
  confirmButtonText: 'Cool'
})
                }
            }
        });
    </script>
</div>
@endsection
