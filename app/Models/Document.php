<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Document extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'folder_id',
        'version_group_id',
        'version',
        'is_latest',
        'title',
        'description',
        'file_path',
        'file_name',
        'extension',
        'file_size',
        'mime_type',
        'created_by',
    ];

    public function folder(): BelongsTo
    {
        return $this->belongsTo(Folder::class);
    }

    public function metadata(): HasMany
    {
        return $this->hasMany(DocumentMetadata::class);
    }

    public function shares(): HasMany
    {
        return $this->hasMany(DocumentShare::class);
    }

    public function versions(): HasMany
    {
        return $this->hasMany(Document::class, 'version_group_id', 'version_group_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
