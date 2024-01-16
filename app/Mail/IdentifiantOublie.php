<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class IdentifiantOublie extends Mailable
{
    use Queueable, SerializesModels;

    private $identifiant;
    private $pass;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($identifiant, $pass)
    {
        $this->identifiant = $identifiant;
        $this->pass = $pass;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.identifiantOublie')
            ->subject("Fédération Photographique de France - rappel identifiant pour le concours Open Fed")
            ->with(['identifiant' => $this->identifiant, 'pass' => $this->pass]);
    }
}
