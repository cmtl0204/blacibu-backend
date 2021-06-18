<?php

namespace App\Mail\Authentication;

use App\Models\Authentication\System;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class UserCreationMailable extends Mailable
{
    use Queueable, SerializesModels;

    private $data;
    private $system;

    public function __construct($data, $system = null)
    {
        $this->subject = 'Creación de Usuario';
        $this->data = $data;
        $this->system = System::find($system);
    }

    public function build()
    {
        return $this->view('mails.authentication.user-creation')
            ->with(['data' => json_decode($this->data), 'system' => $this->system]);
    }
}
