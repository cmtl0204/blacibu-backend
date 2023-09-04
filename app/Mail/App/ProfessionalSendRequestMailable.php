<?php

namespace App\Mail\App;

use App\Models\Authentication\System;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ProfessionalSendRequestMailable extends Mailable
{
    use Queueable, SerializesModels;

    private $data;
    private $system;

    public function __construct($data, $system = 1)
    {
        $this->subject = 'Blacibu - Recaudos';
        $this->data = $data;
        $this->system = System::find($system);
    }

    public function build()
    {
        return $this->view('mails.app.professional-recaudos')
            ->with(['data' => json_decode($this->data), 'system' => $this->system]);
    }
}
