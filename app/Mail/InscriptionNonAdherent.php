<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class InscriptionNonAdherent extends Mailable
{
    use Queueable, SerializesModels;

    private $prenom;
    private $nom;
    private $crypt;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($prenom, $nom, $crypt)
    {
        $this->prenom = $prenom;
        $this->nom = $nom;
        $this->crypt = $crypt;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.inscriptionNonAdherent')
            ->subject("Fédération Photographique de France - inscription au concours Open Fed")
            ->with(['nom' => $this->nom, 'prenom' => $this->prenom, 'crypt' => $this->crypt]);
    }
}
