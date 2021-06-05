<?php

namespace App\Models\App;

use Dyrynda\Database\Support\CascadeSoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as Auditing;

// Traits State
use Illuminate\Database\Eloquent\SoftDeletes;

class Document extends Model implements Auditable
{
    use HasFactory;
    use Auditing;
    use SoftDeletes;
    use CascadeSoftDeletes;

    protected static $instance;

    protected $connection = 'pgsql-app';
    protected $table = 'app.documents';

    protected $fillable = ['aditional_information'];
    protected $cascadeDeletes = ['file'];
    // Instance
    public static function getInstance($id)
    {
        if (is_null(static::$instance)) {
            static::$instance = new static;
        }
        static::$instance->id = $id;
        return static::$instance;
    }

    // Relationsships
    public function professional()
    {
        return $this->belongsTo(Professional::class);
    }
    public function type()
    {
        return $this->belongsTo(Catalogue::class);
    }

    public function file()
    {
        return $this->morphOne(File::class, 'fileable');
    }

    public function status()
    {
        return $this->belongsTo(Status::class);
    }
}
