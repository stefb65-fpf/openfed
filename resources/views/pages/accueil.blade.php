@extends('layouts.default')

@section('content')
    <div class="row mt-3 mb-2">
        <div class="col-12 text-center">
            <h2>
                Concours organisé par la Fédération Photographique de France
            </h2>
        </div>
    </div>
    <div class="row mt-3">
        <div class="col-12 text-center">
            Ce concours est ouvert à tous les photographes amateurs de France, qu'ils soient adhérents ou non de la FPF.
        </div>
    </div>
    <hr>
    <div class="row mt-3">
        <div class="col-12 col-lg-6">
            <div class="card">
                <div class="card-header text-center">
                    <h3>Règlement</h3>
                </div>
                <div class="card-body">
                    <ul>
                        <li>
                            Thème libre, les photos doivent être en couleur et une seule photo animalière par participant est acceptée
                        </li>
                        <li class="mt-2">
                            L'envoi des photos se fait par fichier numérique
                        </li>
                        <li class="mt-2">
                            Les images doivent respecter les contraintes suivantes : leur plus grande dimension doit être de 1920 pixels, être au format JPG, inclure le profil colorimétrique sRGB et ne pas dépasser un poids maximum de 3 Mo
                        </li>
                        <li class="mt-2">
                            Chaque auteur participera avec un maximum de 3 images. Le classement auteur se fera avec le cumul des 3 photos les mieux classées de l'auteur.
                        </li>
                        <li class="mt-2">
                            Les 5 meilleurs auteurs seront récompensés.
                        </li>
                    </ul>
                </div>
                <div class="card-footer text-center">
                    <a href="{{ url('/storage/app/public/Reglement.pdf') }}" class="text text-warning" target="_blank">Télécharger le règlement</a>
                </div>
            </div>
        </div>

        <div class="col-12 col-lg-6">
            <div class="card">
                <div class="card-header text-center">
                    <h3>Inscription / Connexion</h3>
                </div>
                <div class="card-body">
{{--                    si la phase de saisie n'a pas commencé--}}
                    @if($lacompet->statut == 1)
                        <div class="alert alert-danger text-center mt-5 mb-5" style="width: max-content; margin: 0 auto;">
                            Les inscriptions ne sont pas ouvertes actuellement
                        </div>
                        <div class="mb-5 text-center" style="font-size: 1.5rem">
                            Ouverture des inscriptions le {{ $lacompet->date_debut_saisie }}
                        </div>
                    @endif
{{--                    si la saisie est terminée mais les résultats non encore disponibles--}}
                    @if($lacompet->statut > 2 && $lacompet->statut < 6)
                        <div class="alert alert-danger text-center mt-5 mb-5">
                            Le jugement du concours Open Fed est en cours. Vous pourrez accéder à votre espace dès que les résultats seront disponibles
                        </div>
                        <div class="mb-4 text-center" style="font-size: 1.5rem">
                            Résultats disponibles le {{ $lacompet->date_resultat }}
                        </div>
                    @endif

{{--                    si on est en phase de saisie ou en phase de résultats, on peut se connecter--}}
                    @if($lacompet->statut == 2 || $lacompet->statut == 6)
                        <div class="row">
                            <div class="col-12">
                                <h4 style="text-decoration: underline">Connexion</h4>
                            </div>
                            <div class="col-12">
                                Vous êtes adhérent FPF ou vous êtes déjà inscrit, renseigner vos identifiants
                            </div>
                            <div class="col-12 mt-3">
                                <form action="">
                                    <div class="row">
                                        <div class="col-12 col-lg-5">
                                            <input type="text" class="form-control" name="login" id="loginOpen" placeholder="Adresse email" maxlength="100">
                                        </div>
                                        <div class="col-12 col-lg-3">
                                            <input type="password" class="form-control" name="password" id="passwordOpen" placeholder="mot de passe" maxlength="50">
                                        </div>
                                        <div class="col-12 col-lg-4">
                                            <button type="button" id="btnConnexionOpen" class="btn btn-success">ME CONNECTER</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="col-12">
                                <a href="https://fpf.federation-photo.fr/forgotPassword" target="_blank" style="cursor:pointer;" class="text text-warning"><small>mot de passe oublié</small></a>
{{--                                <a id="forgotId" style="cursor:pointer;" class="text text-warning"><small>mot de passe oublié</small></a>--}}
                            </div>
                        </div>
                    @endif

{{--                    si on est en phase de saisie, on peut s'inscrire--}}
                    @if($lacompet->statut == 2)
                        <hr>
                        <div class="row mt-2">
                            <div class="col-12">
                                <h4 style="text-decoration: underline">Inscription</h4>
                            </div>
                            <div class="col-12">
                                Vous n'êtes pas adhérent FPF et vous n'êtes pas encore inscrit, veuillez renseigner votre adresse email et nous vous enverons un lien pour valider votre inscription
                            </div>
                            <div class="col-12 mt-3">
                                <form>
                                    <div class="row">
                                        <div class="col-lg-9">
                                            <div class="row">
                                                <div class="col-lg-6">
                                                    <input type="text" class="form-control" id="prenomInscription" placeholder="votre prénom">
                                                </div>
                                                <div class="col-lg-6">
                                                    <input type="text" class="form-control" id="nomInscription" placeholder="votre nom">
                                                </div>
                                            </div>
                                            <div class="mt-1">
                                                <input type="email" class="form-control" name="mailInscription" id="mailInscription" placeholder="name@example.com" maxlength="70">
                                            </div>
                                            <div class="mt-1">
                                                <input type="password" class="form-control" name="passwordInscription" id="passwordInscription" placeholder="mot de passe 8 caractères minimum" maxlength="70">
                                            </div>
                                            <div class="form-check mb-3">
                                                <input type="checkbox" class="form-check-input" id="validationReglementInscription" required>
                                                <label class="form-check-label" for="validationReglementInscription">Je m'engage à respecter le <a href="{{ url('/storage/app/public/Reglement.pdf') }}" class="text text-warning">règlement</a> proposé pour la compétition</label>
                                            </div>
                                        </div>
                                        <div class="col-lg-3">
                                                <button type="button" class="btn btn-primary disabled" id="btnValidInscription">M'INSCRIRE</button>

                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    @endif

                    @if($lacompet->statut == 6)
                        <hr>
                        <div class="alert alert-success text-center mt-5 mb-2" style="width: max-content; margin: 0 auto;">
                            Les résultats sont disponibles
                        </div>
                    @endif


                </div>
                <div class="card-footer text-center">
                    @if($old_compet_id != 0)
                        <a href="https://copain.federation-photo.fr/webroot/competitions/classement/auteurs/577" target="_blank" class="text text-warning">Classement du dernier concours Open Fed</a>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <div class="row mt-5">
        <div class="col-12 col-lg-4 text-center">
            <a href="https://www.nikon.fr/fr_FR/" target="_blank">
                <img src="{{ url('/storage/app/public/blank-160x160.png') }}" alt="NIKON" style="width: 100px;">
            </a>
        </div>
        <div class="col-12 col-lg-4 text-center">
            <a href="https://www.reponsesphoto.fr/" target="_blank">
                <img src="{{ url('/storage/app/public/blank-160x160.png') }}" alt="Réponse Photo" style="width: 200px; margin-top: 20px">
            </a>
        </div>
        <div class="col-12 col-lg-4 text-center">
            <a href="https://federation-photo.fr" target="_blank">
                <img src="{{ url('/storage/app/public/Logo-FPF-160x160.jpg') }}" alt="Fédération Photographique de France" style="width: 100px;">
            </a>
        </div>
    </div>
    <span id="route_for_inscription_openfed" style="display: none">{{ route('ajax.inscriptionOpenfed') }}</span>
    <span id="route_for_forgot_id" style="display: none">{{ route('ajax.forgotId') }}</span>
    <span id="route_for_check_login_open" style="display: none">{{ route('ajax.checkLoginOpen') }}</span>
@endsection
@section('js')
    <script src="{{ asset('js/accueil.js') }}"></script>
@endsection
