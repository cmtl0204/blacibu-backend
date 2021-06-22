<?php

namespace App\Models\App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as Auditing;
use Illuminate\Database\Eloquent\SoftDeletes;

class Email extends Model implements Auditable
{
    use HasFactory;
    use Auditing;
    use SoftDeletes;

    // protected $connection = 'pgsql-app';
    protected $table = 'emails';

    protected $fillable = [
        'to',
        'from',
        'from_name',
        'subject',
        'body',
    ];
}
