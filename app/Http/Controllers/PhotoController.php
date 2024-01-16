<?php

namespace App\Http\Controllers;

use App\Models\Competition;
use App\Models\Photo;
use App\Models\Utilisateur;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Intervention\Image\ImageManagerStatic;

class PhotoController extends Controller
{
    public function upload(Request $request, $identifiant, $competition_id, $position) {
        $utilisateur = Utilisateur::where('identifiant', $identifiant)->first();
        $lacompet = Competition::where('id', $competition_id)->first();
        if ($utilisateur && $lacompet) {
            // on récupère les photos de l'utilisateur
            $photos = Photo::where('competitions_id', $lacompet->id)->where('participants_id', $identifiant)->get();
            $num_photo = sizeof($photos) + 1;
            if ($num_photo <= $lacompet->max_photos_auteur) {
                // on contrôle la photo
                $file = $_FILES['file'];
                $size = filesize($file['tmp_name']);
                if ($size > 3145728) {
                    return json_encode(array('code' => '10'));
                }

                $tabtype = array('image/jpg', 'image/jpeg');
                if (!in_array($file['type'], $tabtype)) {
                    return json_encode(array('code' => '20'));
                }

                $tabphoto     = getimagesize($file['tmp_name']);
                $hauteur      = $tabphoto[1];
                $largeur      = $tabphoto[0];
                if ($hauteur != 1920 && $largeur != 1920) {
                    return json_encode(array('code' => '30'));
                }
                if ($largeur > 1920) {
                    return json_encode(array('code' => '31'));
                }
                if ($hauteur > 1920) {
//                if ($hauteur > 1080) {
                    return json_encode(array('code' => '32'));
                }

                // on upload la photo dans un répertoire temps
                $name = $identifiant.'_'. time().'.jpg';
                $dirname = '/home/vhosts/copain.federation-photo.fr/htdocs/webroot/upload/ptemp/';
                if (move_uploaded_file($file['tmp_name'], $dirname.$name)) {
//                    if ($largeur > $hauteur) {
////                        $ratio = $largeur / 230;
//                        $newhauteur = $hauteur *230 / 1920;
//                        $margin_dif = (230 - $newhauteur ) / 2;
//                        if ($margin_dif < 0) {
//                            $chaine_attr_image = "max-height:230px; max-width: 230px;";
//                        } else {
//                            $chaine_attr_image = "max-height:230px; max-width: 230px; margin-top:".$margin_dif."px;";
//                        }
//
//                    }
//                    else {
                        $chaine_attr_image = "max-height:320px; max-width: 320px;";
//                    }
                    return json_encode(array('code' => '0', 'name' => $name, 'chaine' => $chaine_attr_image,
                        'url' => 'https://copain.federation-photo.fr/webroot/upload/ptemp/'));
                } else {
                    return json_encode(array('code' => '40'));
                }

            }
        }
    }

    public function savePhotoOpen(Request $request) {
        $lacompet = Competition::where('id', $request->compet)->first();
        $utilisateur = Utilisateur::where('identifiant', $request->identifiant)->first();
        if ($utilisateur && $lacompet) {
            // on récupère les photos pour l'utilisateur
            $photos = Photo::where('competitions_id', $lacompet->id)->where('participants_id', $utilisateur->identifiant)->get();
            $num_photo = sizeof($photos) + 1;
            if ($num_photo <= $lacompet->max_photos_auteur) {
                // on peut enregistrer
                $ean = str_replace('-', '', $utilisateur->identifiant).str_pad($num_photo, 2, '0', STR_PAD_LEFT);

                $exist_photo = Photo::where('ean', $ean)->where('competitions_id', $lacompet->id)->first();
                if (!$exist_photo) {
                    // on déplace la photo
                    $fichier = '/home/vhosts/copain.federation-photo.fr/htdocs/webroot/upload/ptemp/'.$request->photo;
                    $nom_fichier = $ean.'.jpg';
                    $newdir ='/home/vhosts/copain.federation-photo.fr/htdocs/webroot/upload/competitions/national/'.$lacompet->saison.'/compet'.$lacompet->numero.'/';
                    $dest = $newdir.$nom_fichier;
                    if (rename($fichier, $dest)) {
                        // on créé une vignette
                        $tabphoto     = getimagesize($dest);
                        $hauteur      = $tabphoto[1];
                        $largeur      = $tabphoto[0];
                        $thumb = $newdir.'thumbs/'.$nom_fichier;
                        if ($largeur > $hauteur) {
                            ImageManagerStatic::make($dest)->resize(900, null, function ($constraint) {
                                $constraint->aspectRatio();
                            })->save($thumb);
                        } else {
                            ImageManagerStatic::make($dest)->resize(null, 900, function ($constraint) {
                                $constraint->aspectRatio();
                            })->save($thumb);
                        }

                        // on enregistre en base
                        $max_photo = Photo::where('competitions_id', $lacompet->id)->orderByDesc('saisie')->first();
                        if ($max_photo) {
                            $num_saisie = $max_photo->saisie + 1;
                        } else {
                            $num_saisie = 1;
                        }
                        $datap = array('ean' => $ean, 'competitions_id' => $lacompet->id, 'participants_id' => $utilisateur->identifiant,
                            'titre' => filter_var($request->titre, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES), 'saisie' => $num_saisie,
                            'openfed' => $num_photo);
                        Photo::insert($datap);

                        return json_encode(array('code' => '0', 'num_photo' => $num_photo));
                    }
                }
            }
        }
        return json_encode(array('code' => '10'));
    }

    public function deletePhotoOpen(Request $request) {
        $lacompet = Competition::where('id', $request->compet)->first();
        $utilisateur = Utilisateur::where('identifiant', $request->identifiant)->first();
        if ($utilisateur && $lacompet) {
            $ean = str_replace('-', '', $utilisateur->identifiant).str_pad($request->photo, 2, '0', STR_PAD_LEFT);
            // on supprime la photo dans la base
            Photo::where('ean', $ean)->where('competitions_id', $lacompet->id)->delete();

            // on supprime le fichier photo
            $dir ='/home/vhosts/copain.federation-photo.fr/htdocs/webroot/upload/competitions/national/'.$lacompet->saison.'/compet'.$lacompet->numero.'/';
            $dir_thumbs ='/home/vhosts/copain.federation-photo.fr/htdocs/webroot/upload/competitions/national/'.$lacompet->saison.'/compet'.$lacompet->numero.'/thumbs/';
            $fichier = $dir.$ean.'.jpg';
            $thumb = $dir_thumbs.$ean.'.jpg';
            if (file_exists($fichier)) {
                unlink($fichier);
            }
            if (file_exists($thumb)) {
                unlink($thumb);
            }

            // on regarde s'il faut renommer les autres fichiers
            $photos = Photo::where('competitions_id', $lacompet->id)->where('participants_id', $utilisateur->identifiant)->orderBy('ean')->get();
            $j = 1;
            foreach ($photos as $photo) {
                if (substr($photo->ean, 11, 1) !== $j) {
                    // on renomme le fichier
                    $new_ean = substr($photo->ean, 0, 11).$j;
                    $prev = $photo->ean.'.jpg';
                    $dest = $new_ean.'.jpg';
                    rename($dir.$prev, $dir.$dest);
                    rename($dir_thumbs.$prev, $dir_thumbs.$dest);

                    // on met à jour en base
                    $datap = array('ean' => $new_ean, 'openfed' => $j);
                    DB::table('photos')->where('id', $photo->id)->update($datap);
                }
                $j++;
            }
            return json_encode(array('code' => '0'));
        }
        return json_encode(array('code' => '10'));
    }

    public function updateTitlePhotoOpen(Request $request) {
        $lacompet = Competition::where('id', $request->compet)->first();
        $utilisateur = Utilisateur::where('identifiant', $request->identifiant)->first();
        if ($utilisateur && $lacompet) {
            $ean = str_replace('-', '', $utilisateur->identifiant) . str_pad($request->ref, 2, '0', STR_PAD_LEFT);
            $titre = filter_var($request->titre, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
            $data = array('titre' => $titre);
            DB::table('photos')->where('ean', $ean)->where('competitions_id', $lacompet->id)->update($data);
            return json_encode(array('code' => '0'));
        }
        return json_encode(array('code' => '10'));
    }
}
