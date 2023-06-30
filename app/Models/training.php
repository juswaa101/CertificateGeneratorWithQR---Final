<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class training extends Model
{
    use HasFactory;
    public $table = 'training';
    public $primary_key = 'training_id';
    public $timestamps = true;
    protected $fillable = [
      'training',
      'image',
      'img',
      'logo',
      'company',
      'from_start_date',
      'until_end_date',
      'signature',
      'description',
      'organizer',
      'position',
      'status'
    ];
}
