@extends('layouts.default')

@section('content')
    <div class="row mt-3 mb-2">
        <div class="col-12 col-lg-10 offset-lg-1 text-center">
            <h2>
                Concours organisé par la Fédération Photographique de France
            </h2>
        </div>
        <div class="col-12 col-lg-1">
            <a class="btn btn-secondary" href="{{ route('logout') }}">Déconnexion</a>
        </div>
    </div>

    <div class="row" style="font-size: 20px">
        <div class="col-12 text-center mt-5">
            Les résultats pour le concours OPENFED {{ $lacompet->saison }} sont disponibles.
            Seuls les meilleurs auteurs sont classés.<br>
            Si vos oeuvres ne figurent pas dans la liste, cela signifie qu'elles n'ont pas été retenues après la phase de pré-sélection.
        </div>
        @if(sizeof($lesphotos) > 0)
            <div class="col-12 text-center mt-5">
                <h2>Classement de vos oeuvres</h2>
                <table class="table">
                    <thead>
                    <tr>
                        <th></th>
                        <th>Titre</th>
                        <th>Identifiant</th>
                        <th>Total</th>
                        <th>Place</th>
                    </tr>
                    </thead>
                    <tboby>
                        @foreach($lesphotos as $photo)
                            <tr>
                                <td><img src="{{ $photo->path }}" style="max-width: 150px; max-height: 150px; margin-top: {{ $photo->margin }}px"></td>
                                <td>{{ $photo->titre }}</td>
                                <td>{{ $photo->ean }}</td>
                                <td>{{ $photo->total }}</td>
                                <td>{{ $photo->place }}</td>
                            </tr>
                        @endforeach
                    </tboby>
                </table>
            </div>
        @endif
        <div class="col-12 text-center mt-5 mb-5">
            <a href="https://copain.federation-photo.fr/webroot/competitions/classement/auteurs/{{ $lacompet->id }}" class="btn btn-success">VOIR TOUS LES RESULTATS</a>
        </div>
    </div>
@endsection
