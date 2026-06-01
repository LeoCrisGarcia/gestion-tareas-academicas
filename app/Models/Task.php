<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
#[Fillable(['subject_id', 'user_id', 'title', 'description', 'due_date', 'priority', 'status'])]
class Task extends Model
{
    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class);
    }
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    protected function casts(): array
    {
    return [
        'due_date' => 'date',
    ];
    }
    public function tags(): BelongsToMany
    {
    return $this->belongsToMany(Tag::class);
    }
}