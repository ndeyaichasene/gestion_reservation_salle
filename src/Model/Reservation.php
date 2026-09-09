<?php

declare(strict_types=1);

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class Reservation extends Model
{
    protected $table = 'reservations';

    protected $fillable = [
        'salle_id',
        'responsable',
        'email',
        'motif',
        'date_debut',
        'date_fin',
        'statut',
    ];

    protected $casts = [
        'date_debut' => 'immutable_datetime',
        'date_fin'   => 'immutable_datetime',
    ];

    protected $dateFormat = 'Y-m-d H:i:s';

    public function salle(): BelongsTo
    {
        return $this->belongsTo(Salle::class);
    }
}