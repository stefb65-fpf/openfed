@extends('layouts.default')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card text-white bg-secondary mt-5 mb-3" style="width: 70%; margin: 0 auto">
            <div class="card-header text-center">Fédération Photographique de France</div>
            <div class="card-body">
                <h4 class="card-title">Inscription confirmée</h4>
                <p class="card-text">
                    Votre inscription est confirmée. Vous pouvez désormais participer aux concours Open Fed proposé par la Fédération Photographique de France<br>
                    Pour vous connecter, utilisez votre adresse email et le mot de passe renseigné lors de votre inscription.
                <div class="text-center mt-3">
                    <a href="https://open.federation-photo.fr" class="btn btn-success">ME CONNECTER</a>
                </div>
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
