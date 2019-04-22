@extends('templateLogin')
@section('content')
    <form class="form-horizontal" method="post" action="#">


        {{ csrf_field() }}
        <input type="hidden" name="id" value="{{ $user->id }}">
        <div class="form-group">
            <label for="email" class="cols-sm-2 control-label">Nome*</label>
            <div class="cols-sm-10">
                <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-user fa" aria-hidden="true"></i></span>
                    <input type="text" class="form-control" name="name" id="name" value="{{ $user->name }}" placeholder="Entre com o seu nome" required/>
                </div>
            </div>
        </div>
        <div class="form-group">
            <label for="email" class="cols-sm-2 control-label">Tipo*</label>
            <div class="cols-sm-10">
                <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-sitemap fa" aria-hidden="true"></i></span>
                    <input type="text" class="form-control" value="{{ $user->type }}" disabled="">

                </div>
            </div>
        </div>
        <div class="form-group">
            <label for="email" class="cols-sm-2 control-label">Email*</label>
            <div class="cols-sm-10">
                <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-envelope fa" aria-hidden="true"></i></span>
                    <input type="text" class="form-control" name="email" id="email" value="{{ $user->email }}" placeholder="Entre com o seu email" disabled="" required/>
                </div>
            </div>
        </div>



        <div class="form-group">
            <label for="password" class="cols-sm-2 control-label">Senha*</label>
            <div class="cols-sm-10">
                <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-lock fa-lg" aria-hidden="true"></i></span>
                    <input type="password" class="form-control" name="password" id="password"  placeholder="Entre com a sua senha"/>
                </div>
            </div>
        </div>
        <div class="form-group">
            <label for="email" class="cols-sm-2 control-label">CPF</label>
            <div class="cols-sm-10">
                <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-shield fa" aria-hidden="true"></i></span>
                    <input type="number" class="form-control" name="CPF" id="CPF" value="{{ $user->CPF }}"  placeholder="Entre com o seu cpf(opcional)"/>
                </div>
            </div>
        </div>




        <div class="form-group ">
            <button type="submit" class="btn btn-primary btn-lg btn-block login-button">Cadastrar</button>
        </div>
        <div class="login-register">
            <a href="admin">Login</a>
        </div>
    </form>
@endsection