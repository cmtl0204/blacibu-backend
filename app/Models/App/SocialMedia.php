<?php

namespace App\Models\App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as Auditing;
use Illuminate\Database\Eloquent\SoftDeletes;

class Socialmedia extends Model implements Auditable
{
    use HasFactory;
    use Auditing;
    use SoftDeletes;

    private static $instance;

    protected $connection = 'pgsql-app';
    protected $table = 'app.socialmedia';

    protected $fillable = [
        'name',
        'icon',
        'logo',
    ];

    public static function getInstance($id)
    {
        if (is_null(static::$instance)) {
            static::$instance = new static;
        }
        static::$instance->id = $id;
        return static::$instance;
    }

    public function professionals()
    {
        return $this->morphedByMany(Professional::class, 'socialmediable', 'app.socialmediable')
            ->withPivot('user','url')->withTimestamps();
    }
}
