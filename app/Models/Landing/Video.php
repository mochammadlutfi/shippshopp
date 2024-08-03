<?php

namespace App\Models\Landing;

use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;

class Video extends Model
{
    use Sluggable;


    protected $table = 'videos';
    protected $primaryKey = 'id';

    protected $fillable = [
        'id', 'title',
    ];

    /**
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */
    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'title'
            ]
        ];
    }
    
}
