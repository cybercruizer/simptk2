<?php

namespace App\Models;

use App\Models\Usulan;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Dokumen extends Model
{
    protected $guarded=[];
    /**
     * Get the usulan that owns the Dokumen
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function usulan(): BelongsTo
    {
        return $this->belongsTo(Usulan::class);
    }
    /**
     * Get the user that owns the Dokumen
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}