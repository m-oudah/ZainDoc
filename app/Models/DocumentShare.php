<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DocumentShare extends Model
{
    use HasFactory;

    protected $fillable = [
        'document_id',
        'share_token',
        'password',
        'expires_at',
        'access_count',
        'created_by',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'access_count' => 'integer',
    ];

    protected $hidden = [
        'password',
    ];

    public function document(): BelongsTo
    {
        return $this->belongsTo(Document::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
