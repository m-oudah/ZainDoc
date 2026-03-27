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
        'folder_id',
        'keywords',
    ];

    protected $casts = [
        'rules' => 'array',
        'keywords' => 'array',
    ];

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function folder(): BelongsTo
    {
        return $this->belongsTo(Folder::class);
    }
}
