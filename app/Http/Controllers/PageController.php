<?php

namespace App\Http\Controllers;

use App\Models\Competition;
use App\Models\Photo;
use App\Models\Utilisateur;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class PageController extends Controller
{
    public function accueil() {
//        $lacompet = Competition::where('id', 577)->orderByDesc('saison')->first();
        $lacompet = Competition::where('type', 4)->orderByDesc('saison')->first();
        $date_saisie = Carbon::createFromFormat('Y-m-d', $lacompet->date_debut_saisie);
        $lacompet->date_debut_saisie = $this->formatDate($lacompet->date_debut_saisie);
        $lacompet->date_resultat = $this->formatDate($lacompet->date_resultat);

        // on recherche l'ancienne compétition
        $old_compet_id = 0;
        $old_compet = Competition::where('type', 4)->where('saison', '<', $lacompet->saison)->orderByDesc('saison')->first();
        if ($old_compet) {
            $old_compet_id = $old_compet->id;
        }

        return view('pages.accueil', compact('lacompet', 'old_compet_id'));
    }

    public function resultats() {
        if (!isset($_COOKIE['openfed_identifiant']) || !isset($_COOKIE['openfed_token'])) {
            return redirect()->route('accueil');
        }
        $lacompet = Competition::where('type', 4)->orderByDesc('saison')->first();
        // on est dans la phase d'inscription, on regarde si l'utilisateur a déjà des photos pour ce concours
        $identifiant = $_COOKIE['openfed_identifiant'];
        $utilisateur = Utilisateur::where('identifiant', $identifiant)->first();
        if (!$utilisateur) {
            return redirect()->route('accueil');
        }

        $lesphotos = Photo::where('competitions_id', $lacompet->id)->where('participants_id', $identifiant)->orderBy('place', 'ASC')->get();

        foreach ($lesphotos as $photo) {
            // on cherche le chemi
            $photo->path = 'https://copain.federation-photo.fr/webroot/upload/competitions/national/'.$lacompet->saison.'/compet'.$lacompet->numero.'/thumbs/'.$photo->ean.'.jpg';
            $fichier ='/home/vhosts/copain.federation-photo.fr/htdocs/webroot/upload/competitions/national/'.$lacompet->saison.'/compet'.$lacompet->numero.'/'.$photo->ean.'.jpg';
            $tabsize = getimagesize($fichier);
            $hauteur      = $tabsize[1];
            $largeur      = $tabsize[0];
            $margin_top = 0;
            if ($largeur > $hauteur) {
                $newhauteur = $hauteur *150 / 1920;
                $margin_top = (150 - $newhauteur) / 2;
            }
            $photo->margin = $margin_top;
        }

        return view('pages.resultats', compact('utilisateur', 'lesphotos', 'lacompet'));
    }

    public function inscription() {
        if (!isset($_COOKIE['openfed_identifiant']) || !isset($_COOKIE['openfed_token'])) {
            return redirect()->route('accueil');
        }
        $lacompet = Competition::where('type', 4)->orderByDesc('saison')->first();
        if ($lacompet->statut == 6) {
            return redirect()->route('resultats');
        }
        if ($lacompet->statut != 2) {
            unset($_COOKIE['openfed_identifiant']);
            unset($_COOKIE['openfed_token']);
            return redirect()->route('accueil');
        }
        // on est dans la phase d'inscription, on regarde si l'utilisateur a déjà des photos pour ce concours
        $identifiant = $_COOKIE['openfed_identifiant'];
        $utilisateur = Utilisateur::where('identifiant', $identifiant)->first();
        if (!$utilisateur) {
            return redirect()->route('accueil');
        }
        $lesphotos = Photo::where('competitions_id', $lacompet->id)->where('participants_id', $identifiant)->get();
        $photos = array();

        foreach ($lesphotos as $photo) {
            // on cherche le chemi
            $ordre = substr($photo->ean, 11, 1);
            $photo->path = 'https://copain.federation-photo.fr/webroot/upload/competitions/national/'.$lacompet->saison.'/compet'.$lacompet->numero.'/thumbs/'.$photo->ean.'.jpg';
            $photos[$ordre] = $photo;

            $fichier ='/home/vhosts/copain.federation-photo.fr/htdocs/webroot/upload/competitions/national/'.$lacompet->saison.'/compet'.$lacompet->numero.'/'.$photo->ean.'.jpg';
            $tabsize = getimagesize($fichier);
            $hauteur      = $tabsize[1];
            $largeur      = $tabsize[0];
            $margin_top = 0;
            if ($largeur > $hauteur) {
                $newhauteur = $hauteur *230 / 1920;
                $margin_top = (230 - $newhauteur) / 2;
            }
            $photo->margin = $margin_top;
        }

        return view('pages.inscription', compact('utilisateur', 'photos', 'lacompet'));
    }

    public function acceptationCookies() {
        $cookie_rgpd = cookie('openfed_rgpd', 1, 1440*365);
        $reponse = array('code' => '0');
        return response(json_encode($reponse))->cookie($cookie_rgpd);
    }

    protected function formatDate($date_in) {
        $tab_semaine = array('1' => 'Lundi', '2' => 'Mardi', '3' => 'Mercredi', '4' => 'Jeudi', '5' => 'Vendredi', '6' => 'Samedi', '7' => 'Dimanche');
        $tab_month = array('1' => 'Janvier', '2' => 'Février', '3' => 'Mars', '4' => 'Avril', '5' => 'Mai', '6' => 'Juin', '7' => 'Juillet', '8' => 'Août',
            '9' => 'Septembre', '10' => 'Octobre', '11' => 'Novembre', '12' => 'Décembre');
        $transform_date = Carbon::createFromFormat('Y-m-d', $date_in);
        $date_temp = $transform_date->format('N j n Y');
        $tab_date = explode(' ', $date_temp);
        $tab_date[0] = $tab_semaine[$tab_date[0]];
        $tab_date[2] = $tab_month[$tab_date[2]];

        return implode(' ', $tab_date);
    }

}
