<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Certificate extends Model
{
    use HasFactory;

    public $primaryKey = 'id';
    protected $table = 'certificates'; 
    public $timestamps = true;
    protected $fillable = [
        'training_id',
        'certificate_id',
        'signature',
        'name',
        'email',
        'description',
        'organizer',
        'position',
    ];
}
