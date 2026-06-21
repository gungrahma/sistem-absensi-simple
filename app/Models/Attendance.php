<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['user_id', 'tanggal', 'jam_masuk', 'jam_keluar', 'status_masuk', 'telat_menit'])]
class Attendance extends Model
{
    protected function casts(): array
    {
        return [
            'tanggal' => 'date',
            'telat_menit' => 'integer',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getDurasiKerjaAttribute(): ?string
    {
        if (! $this->jam_masuk || ! $this->jam_keluar) {
            return null;
        }

        $masuk  = strtotime($this->tanggal . ' ' . $this->jam_masuk);
        $keluar = strtotime($this->tanggal . ' ' . $this->jam_keluar);
        $diff   = max(0, $keluar - $masuk);

        $jam  = floor($diff / 3600);
        $menit = floor(($diff % 3600) / 60);

        return sprintf('%dj %dm', $jam, $menit);
    }
}
