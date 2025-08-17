<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pengumuman extends Model
{
    protected $table = 'pengumumans';
    
    protected $fillable = [
        'judul',
        'isi',
        'tanggal_pengumuman',
        'user_id',
        'is_active',
        'kategori',
    ];

    /**
     * Get the user that created the Pengumuman
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
