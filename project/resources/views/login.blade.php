@extends('templateLogin')

@section('content')
            <form class="form-horizontal" id="login" method="post" action="#">


                {{ csrf_field() }}
                <div class="form-group">
                    <label for="email" class="cols-sm-2 control-label">Email</label>
                    <div class="cols-sm-10">
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-envelope fa" aria-hidden="true"></i></span>
                            <input type="text" class="form-control" name="email" id="email"  placeholder="Entre com o seu email"/>
                        </div>
                    </div>
                </div>



                <div class="form-group">
                    <label for="password" class="cols-sm-2 control-label">Senha</label>
                    <div class="cols-sm-10">
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-lock fa-lg" aria-hidden="true"></i></span>
                            <input type="password" class="form-control" name="password" id="password"  placeholder="Entre com a sua senha"/>
                        </div>
                    </div>
                </div>



                <div class="form-group ">
                    <button type="submit" class="btn btn-primary btn-lg btn-block login-button">Login</button>

                </div>
                <div class="login-register">
                    <button id="forget" class="btn btn-primary btn-lg btn-block login-button">Esqueci a senha</button>
                    <a href="registrar">Registrar</a>
                </div>
            </form>
            <script>
                document.getElementById('forget').addEventListener('click',function (ev) {
                    document.getElementById('login').preventDefault;
                    document.getElementById('login').action = 'forget';
                    setTimeout(document.getElementById('login').submit(),50000);
                    //document.getElementById('login').submit();

                });
            </script>
    @endsection