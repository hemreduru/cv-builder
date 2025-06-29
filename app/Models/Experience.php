<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $user_id
 * @property string|null $job_title_en
 * @property string|null $job_title_tr
 * @property string $company_name
 * @property string $start_date
 * @property string|null $end_date
 * @property string|null $description_en
 * @property string|null $description_tr
 * @property int|null $created_by
 * @property int|null $updated_by
 */
class Experience extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'experiences';

    protected $fillable = [
        'user_id',
        'job_title_en',
        'job_title_tr',
        'company_name',
        'start_date',
        'end_date',
        'description_en',
        'description_tr',
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
