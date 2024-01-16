@extends('layouts.default')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card text-white bg-danger mt-5 mb-3" style="width: 70%; margin: 0 auto">
                <div class="card-header text-center">Fédération Photographique de France</div>
                <div class="card-body">
                    <h4 class="card-title">Inscription non prise en compte</h4>
                    <p class="card-text">
                        Votre inscription ne peut pas être prise en compte car aucun utilisateur ne correspond à l'url saisie.<br>
                        Vous pouvez vous inscrire via la page d'accueil.
                        <div class="text-center mt-3">
                            <a href="https://open.federation-photo.fr" class="btn btn-success">M'INSCRIRE</a>
                        </div>
                    </p>
                </div>
            </div>
        </div>
    </div>
@endsection
