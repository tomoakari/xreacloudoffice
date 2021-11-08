<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>CLOUD OFFICE</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@200;600&display=swap" rel="stylesheet">

        <!-- Styles -->
        <style>
            html, body {
                /* background-color: #fff; */
                background-image: url(https://aice.cloud/img/1.jpg);
                background-size: cover;
                color: #636b6f;
                /* font-family: 'Nunito', sans-serif; */
                font-weight: 200;
                height: 100vh;
                margin: 0;
            }

            .full-height {
                height: 100vh;
            }

            .flex-center {
                align-items: center;
                display: flex;
                justify-content: center;
            }

            .position-ref {
                position: relative;
            }

            .top-right {
                position: absolute;
                right: 10px;
                top: 18px;
                background: rgba(255, 255, 255, 0.5);
                /* border: 10px solid rgba(255, 255, 255, 0.1); */
                padding: 5px;
            }

            .content {
                text-align: center;
            }

            .title {
                /*font-size: 84px;*/
                font-size: 50px;
                color: #ffffff;
            }
            .subtitle{
                font-size: 25px;
            }

            .links > a {
                color: #636b6f;
                padding: 0 25px;
                font-size: 13px;
                font-weight: 600;
                letter-spacing: .1rem;
                text-decoration: none;
                text-transform: uppercase;
            }

            .m-b-md {
                /* margin-bottom: 30px; 
                background: #ffffff;
                border: 50px solid #ffffff;
                box-shadow: 0px 0px 70px #393939; */
                background: rgba(0 ,0 ,0 , 0.5);
                border-radius: 10px;
                padding: 60px;
            }
            .footer{
                position: absolute;
                bottom: 0px;
                width: 100%;
                background: rgba(0, 0, 0, 0.5);
                padding: 2px 0px;
                color: #ffffff;
                text-align: center;
            }
        </style>
    </head>
    <body>
        <div class="flex-center position-ref full-height">
            @if (Route::has('login'))
                <div class="top-right links">
                    @auth
                        <a href="{{ url('/home') }}">ダッシュボード</a>
                    @else
                        <a href="{{ route('login') }}">ログイン</a>

                        @if (Route::has('register'))
                            <a href="{{ route('register') }}">新規登録</a>
                        @endif
                    @endauth
                </div>
            @endif

            <div class="content">
                <div class="title m-b-md">
                    CLOUD OFFICE
                    <p class="subtitle">テレワーク時代の社内会議や対外打合せ、<br />直感的に操作できる社内連絡ツールとして、<br />最適な環境をご提供いたします。</p>
                </div>
                

                <!--
                <div class="links">
                    <a href="https://laravel.com/docs">Docs</a>
                    <a href="https://laracasts.com">Laracasts</a>
                    <a href="https://laravel-news.com">News</a>
                    <a href="https://blog.laravel.com">Blog</a>
                    <a href="https://nova.laravel.com">Nova</a>
                    <a href="https://forge.laravel.com">Forge</a>
                    <a href="https://vapor.laravel.com">Vapor</a>
                    <a href="https://github.com/laravel/laravel">GitHub</a>
                </div>
                -->
            </div>
            <div class="footer">Copyright © 2021 AIFORUS,Inc.</div>
        </div>
    </body>
</html>
