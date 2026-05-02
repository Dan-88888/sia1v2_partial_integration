<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    protected $table = 'reg_rooms';

    protected $fillable = ['name', 'building', 'floor', 'capacity'];

    public function sections()
    {
        return $this->hasMany(Section::class);
    }
}
