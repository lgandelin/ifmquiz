@extends('ifmquiz::back.master')

@section('main-content')

    <div class="container page-template login-template">

        <div class="login-wrapper">
            <h1>Connexion</h1>

            <div class="login" style="overflow: hidden;">
                <form role="form" method="POST" action="{{ route('login_handler') }}">
                    <div class="field">
                        <input type="text" class="form-control" name="email" />
                    </div>

                    <div class="field">
                        <input type="password" class="form-control" name="password" autocomplete="off" />
                    </div>

                    <div class="field">
                        <input type="submit" class="button" value="Se connecter" />
                    </div>

                    @if (isset($error))
                        <div class="error" style="margin-top: 3rem;">
                            {{ $error }}
                        </div>
                    @endif

                    {!! csrf_field() !!}
                </form>
            </div>
        </div>

    </div>
@endsection
