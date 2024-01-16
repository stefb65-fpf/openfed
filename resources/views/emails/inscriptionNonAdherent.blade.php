@extends('layouts.email')

@section('content')
    <div class="row">
        <div class="col-12">
            Bonjour {{ $prenom }} {{ $nom }}.
        </div>
        <div class="col-12" style="margin-top: 20px;">
            Vous souhaitez vous inscrire au concours Open Fed proposé par la Fédération Photographique de France.
            Pour cela, vous devez valider votre adresse email en cliquant sur le lien suivant :
            <a href="https://open.federation-photo.fr/confirmation_email/{{ $crypt }}">https://open.federation-photo.fr/confirmation_email/{{ $crypt }}</a>
        </div>
{{--        <div class="col-12" style="margin-top: 20px;">--}}
{{--            Lors de cette validation, vos identifiants vous seront communiqués.--}}
{{--        </div>--}}
    </div>
    <div class="row" style="margin-top: 40px;">
        <div class="col-12">
            Fédération Photographique de France<br>
            <a href="https://federation-photo.fr">https://federation-photo.fr</a>
        </div>
    </div>

@endsection
