<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Registration extends Model
{
    use HasFactory;

    protected $fillable = [
        'department_id',
        'exam_slot_id',
        'registered_by',
    ];

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function examSlot()
    {
        return $this->belongsTo(ExamSlot::class);
    }
}
