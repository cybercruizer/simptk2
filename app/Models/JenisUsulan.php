<?php

namespace App\Models;

use App\Models\Usulan;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class JenisUsulan extends Model
{
    protected $fillable=[
        'nama_usulan',
        'jumlah_dokumen',
        'deskripsi'
    ];
    /**
     * Get all of the usulans for the JenisUsulan
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function usulans(): HasMany
    {
        return $this->hasMany(Usulan::class);
    }
}