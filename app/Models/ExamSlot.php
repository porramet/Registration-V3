<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ExamSlot extends Model
{
    use HasFactory;

    protected $fillable = ['exam_date', 'start_time', 'end_time', 'max_capacity'];

    public function registrations()
    {
        return $this->hasMany(Registration::class);
    }
}
