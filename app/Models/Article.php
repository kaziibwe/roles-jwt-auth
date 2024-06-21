<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    use HasFactory;

    protected $fillable = [
        'doctor_id',
        'content',
        'cover_image',
        'createdAt',

    ];

    public $timestamps = false;


    public function doctor(){
        return $this->belongsTo(Doctor::class, 'doctor_id');

    }

}
