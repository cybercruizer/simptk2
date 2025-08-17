<?php

namespace App\Models;

use App\Models\User;
use App\Models\Dokumen;
use App\Models\JenisUsulan;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Usulan extends Model
{
    protected $table = 'usulans';

    protected $fillable = [
        'created_by',
        'jenis_usulan_id',
        'keterangan',
        'status_1',
        'status_2',
        'approved_at_1',
        'rejected_at_1',
        'approved_at_2',
        'rejected_at_2',
        'approved_by_1',
        'rejected_by_1',
        'approved_by_2',
        'rejected_by_2',
        'reason_rejected_1',
        'reason_rejected_2',
    ];


    public function jenisUsulan(): BelongsTo
    {
        return $this->belongsTo(JenisUsulan::class);
    }
        /**
     * Get the user that owns the Usulan
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class,'created_by');
    }

    public function ApprovedBy1(): BelongsTo
    {
        return $this->belongsTo(User::class,'approved_by_1');
    }

    public function RejectedBy1(): BelongsTo
    {
        return $this->belongsTo(User::class, 'rejected_by_1');
    }
    public function ApprovedBy2(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by_2');
    }

    public function RejectedBy2(): BelongsTo
    {
        return $this->belongsTo(User::class,'rejected_by_2');
    }
    /**
     * Get all of the dokumens for the Usulan
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function dokumens(): HasMany
    {
        return $this->hasMany(Dokumen::class);
    }

}