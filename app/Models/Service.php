<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;
    protected $fillable = ['name','description','short_description','image_path'];
    public function posts()
    {
        return $this->hasMany(Post::class, 'service_id', 'id');
    }
}
