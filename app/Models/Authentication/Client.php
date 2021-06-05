<?php

namespace App\Models\Authentication;

use Laravel\Passport\Client as PassportClient;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as Auditing;



class Client extends PassportClient implements Auditable
{
    use Auditing;

//    public function username()
//    {
//        return 'username';
//    }
}
