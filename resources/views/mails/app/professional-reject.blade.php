@extends('mails.index')
@section('content')
    <div class="row">
        <div class="col-12 text-muted ">
            <h3 class="text-center">Documentos Rechazados</h3>
            <br>
            <p>Por medio de la presente, lamentamos informarle que sus documentos han sido rechzados.</p>

            <p>Para poder ingresar al sistema por favor haga click en el siguiente enlace y verifique sus documentos:</p>
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
