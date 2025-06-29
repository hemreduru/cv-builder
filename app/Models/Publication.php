<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\User;

/**
 * @property int $id
 * @property int $user_id
 * @property string $title
 * @property string|null $url
 * @property string|null $summary_en
 * @property string|null $summary_tr
 * @property int|null $created_by
 * @property int|null $updated_by
 */
class Publication extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'publications';

    protected $fillable = [
        'user_id',
        'title',
        'url',
        'summary_en',
        'summary_tr',
        'created_by',
        'updated_by',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
