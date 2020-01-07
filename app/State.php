<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class State extends Model
{
    /**
     * @var string
     */
    protected $table = 'states';

    /**
     * @var array
     */
    protected $fillable = ['feedback_id', 'device', 'os', 'memory', 'storage'];

    /**
     * @return BelongsTo
     */
    public function feedback(): BelongsTo
    {
        return $this->belongsTo(Feedback::class);
    }
}
