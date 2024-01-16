@extends('layouts.email')

@section('content')
    <div class="row">
        <div class="col-12">
            Bonjour.
        </div>
        <div class="col-12" style="margin-top: 20px;">
            Votre inscription est confirmée pour la participation aux concours Open Fed.<br>
            Lors de votre connexion, vous devrez utiliser els identifiants suivants :
            <ul>
                <li>
                    identifiant: {{ $identifiant }}
                </li>
                <li>
                    mot de passe : {{ $pass }}
                </li>
            </ul>
        </div>
        <div class="col-12" style="margin-top: 20px;">
            Pour vous connecter, rendez vous sur <a href="https://open.federation-photo.fr">https://open.federation-photo.fr</a>
        </div>
    </div>
    <div class="row" style="margin-top: 40px;">
        <div class="col-12">
            Fédération Photographique de France<br>
            <a href="https://federation-photo.fr">https://federation-photo.fr</a>
        </div>
    </div>

@endsection
