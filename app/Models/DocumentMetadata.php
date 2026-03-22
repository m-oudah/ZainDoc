<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DocumentMetadata extends Model
{
    use HasFactory;

    protected $fillable = [
        'document_id',
        'field_id',
        'value',
    ];

    public function document(): BelongsTo
    {
        return $this->belongsTo(Document::class);
    }

    public function field(): BelongsTo
    {
        return $this->belongsTo(MetadataField::class, 'field_id');
    }
}
