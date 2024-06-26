<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    use HasFactory;

    protected $fillable = [
        'doctor_id',
        'link',
        'title',
        'description',
        'image',
    ];


    public function doctor(){
        return $this->belongsTo(Doctor::class, 'doctor_id');
    }
    
}
