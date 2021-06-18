@extends('mails.index')
@section('content')
    <div class="row">
        <div class="col-12 text-muted ">
            <h3 class="text-center">Creación de Usuario</h3>
            <br>
            <p>Bienvenido a <strong>{{$system->name}}</strong></p>
            <p>Por favor revise su usuario y contraseña.</p>
            <p>Usuario: <b>{{$data->user->username}}</b></p>
            <p>Contraseña: <b>{{$data->password}}</b></p>
            <p>Para poder ingresar al sistema por favor haga click en el siguiente enlace:</p>
            <div class=" text-center">
                <a class="btn btn-primary text-center"
                   href="{{$system->redirect}}/auth/login">
                    Iniciar Sesión
                </a>
            </div>
            <br>
            <br>
            <p class="text-muted">Si no puede acceder, copie la siguiente url:</p>
            <p class="text-muted">
                {{$system->redirect}}/auth/login
            </p>
            <br>
            <p>Si no ha solicitado este servicio, repórtelo a su Institución.</p>
        </div>
    </div>
@endsection
