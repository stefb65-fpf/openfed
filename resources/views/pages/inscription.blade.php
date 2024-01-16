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
    <div class="row">
        <div class="col-12">
            Pour cette compétition, vous pouvez inscrire au maximum {{ $lacompet->max_photos_auteur }} oeuvre(s).
            Le format de vos oeuvres doit être:
            <ul>
                <li>dimension maximale de 1920 pixels dans leur plus grande dimension</li>
                <li>au moins une des deux dimensions à 1920 pixels</li>
                <li>format jpeg et poids du fichier < 3 Mo</li>
            </ul>
            Saisissez vos oeuvres une par une en chargeant vos photos puis validez en cliquant sur "Envoyez votre oeuvre".
            Si vous avez fait une erreur, vous pourrez ensuite supprimer votre oeuvre. Pour modifier le titre d'une oeuvre existante,
            modifier le titre dans le champ prévu à cet effet puis appuyez sur Entrée. La modification sera alors prise en compte.
        </div>
    </div>
    <div class="row mt-5 mb-5">
        @for($i=1; $i <= $lacompet->max_photos_auteur; $i++)
            <div class='col-sm-4 col-xs-12'>
                <div class='coloc_pave_inscription'>
                    <div class='coloc_pave_inscription_titre'>Oeuvre n°{{ $i }}</div>
                    <div style='margin-top: 15px;'>
                        <div class="form-horizontal">
                            <div class="form-group row">
                                <label class='col-sm-2 control-label' for="titre_oeuvre_{{ $i }}">Titre: </label>
                                <div class="col-sm-10">
                                    <input data-ref="{{ $i }}" type="text" name="titre_oeuvre" data-compet="{{ $lacompet->id }}" class="form-control" id="titre_oeuvre_{{ $i }}"
                                           placeholder="Titre de votre oeuvre" value="{!!  (isset($photos[$i])) ? $photos[$i]->titre : '' !!}">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div style='margin-top: 15px;' name="plupload">
                        <div class='coloc_photo_inscription' name="browse" id="browse{{ $i }}" data-ref="{{ $i }}">
                            @if(isset($photos[$i]))
                                <img src="{{ $photos[$i]->path }}" style="max-width: 320px; max-height: 320px;">
                            @else
                                <br/>
                                <div class='coloc_photo_inscription_int' name="textUpload" id="textUpload{{ $i }}">
                                    Cliquez ici pour ajouter votre photo.<br/><br/>
                                    Celle-ci apparaîtra dans le cadre une fois chargée.
                                </div>
                                <div class="coloc_loader" name="ajaxLoader" id="ajaxLoader{{ $i }}" style="display: none">
                                    <img src="{{ url('/storage/app/public/ajax-loader.gif') }}" alt="loader" style="height: 230px; width: 230px;">
                                </div>
                            @endif
                        </div>
                    </div>
                    <div style='margin-top: 15px; text-align: center'>
                        <span style="display: none;" name="name_photo" id="name_photo<?= $i ?>"></span>
                        <button class='btn btn-primary disabled' id="register_photo{{ $i }}" style="{{ isset($photos[$i]) ? 'display: none;' : '' }}cursor: pointer" name='register_photo' data-ref="{{ $i }}">Envoyez votre oeuvre n°{{ $i }}</button>
                        <button class='btn btn-danger' style="{{ !isset($photos[$i]) ? 'display: none;' : '' }} cursor: pointer" id="delete_photo{{ $i }}" name='delete_photo' data-ref="{{ $i }}">Supprimez votre oeuvre n°{{ $i }}</button>
                    </div>
                </div>
            </div>
        @endfor

        <div id="pluplaod">
            <div id="browse" data-ref="" data-compet="{{ $lacompet->id }}" data-identifiant="{{ $utilisateur->identifiant }}">
            </div>
        </div>
    </div>
    <span id="route_for_save_photo_open" style="display: none">{{ route('ajax.savePhotoOpen') }}</span>
    <span id="route_for_delete_photo_open" style="display: none">{{ route('ajax.deletePhotoOpen') }}</span>
    <span id="route_for_update_title_photo_open" style="display: none">{{ route('ajax.updateTitlePhotoOpen') }}</span>
@endsection
@section('js')
    <script src="{{ asset('js/plupload.js') }}" ></script>
    <script src="{{ asset('js/inscription.js') }}" ></script>
@endsection
