<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Satpend extends Model
{
    protected $primaryKey = 'npsn';
    protected $fillable= [
        'nama_satpend',
        'alamat',
        'kecamatan_id',
        'kabupaten_id',
        'telepon',
        'email',
        'status',
        'is_active',
        'lintang',
        'bujur',
    ];
    /**
     * Get the user associated with the Satpend
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function user(): HasOne
    {
        return $this->hasOne(User::class, 'satpend_npsn', 'npsn');
    }
}