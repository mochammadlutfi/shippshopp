<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Permission;

class Module extends Model
{
    protected $table = 'modules';
    protected $primaryKey = 'id';
    protected $fillable = [
        'name',
    ];


    public function permission()
    {
        return $this->hasMany(Permission::class, 'module_id');
    }

}
