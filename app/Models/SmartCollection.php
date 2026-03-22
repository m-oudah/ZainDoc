<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SmartCollection extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'rules',
        'created_by',
    ];

    protected $casts = [
        'rules' => 'array',
    ];

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
