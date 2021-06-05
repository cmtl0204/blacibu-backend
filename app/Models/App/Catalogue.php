<?php

namespace App\Models\App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as Auditing;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Authentication\Role;

class Catalogue extends Model implements Auditable
{
    use HasFactory;
    use Auditing;
    use SoftDeletes;

    protected static $instance;

    protected $connection = 'pgsql-app';
    protected $table = 'app.catalogues';


    protected $fillable = [
        'code',
        'name',
        'type',
        'icon',
    ];

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
    public function parent()
    {
        return $this->belongsTo(Catalogue::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Catalogue::class, 'parent_id');
    }

    public function roles()
    {
        return $this->morphedByMany(Role::class, 'catalogueable');
    }

    public function certificates()
    {
        return $this->hasMany(Certificate::class,'type_id');
    }

    public function conferences()
    {
        return $this->hasMany(Conference::class,'type_id');
    }

    public function documents()
    {
        return $this->hasMany(Document::class,'type_id');
    }

    // Mutators
    public function setCodeAttribute($value)
    {
        $this->attributes['code'] = strtoupper($value);
    }

    public function setNameAttribute($value)
    {
        $this->attributes['name'] = strtoupper($value);
    }
}
