<?php

namespace App\Models\App;

// Laravel
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as Auditing;

// Traits State
use Illuminate\Database\Eloquent\SoftDeletes;


// Models
use App\Models\Authentication\User;

class Professional extends Model implements Auditable
{
    use HasFactory;
    use Auditing;
    use SoftDeletes;

    protected static $instance;

    // protected $connection = 'pgsql-app';
    protected $table = 'professionals';

    protected $fillable = ['subregion'];

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
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function documents()
    {
        return $this->hasMany(Document::class);
    }

    public function certificates()
    {
        return $this->hasMany(Certificate::class);
    }

    public function country()
    {
        return $this->belongsTo(Location::class);
    }

    public function conferences()
    {
        return $this->hasMany(Conference::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function payment()
    {
        return $this->hasOne(Payment::class);
    }

    public function images()
    {
        return $this->morphMany(Image::class, 'imageable');
    }

    public function socialmedia()
    {
        return $this->morphToMany(Socialmedia::class, 'socialmediable', 'app.socialmediables')
            ->withPivot('user', 'url')->withTimestamps();
    }

    public function status()
    {
        return $this->belongsTo(Status::class);
    }
}
