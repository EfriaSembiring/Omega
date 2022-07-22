<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Activitylog\Traits\LogsActivity;

class Proyek extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = ['user_id', 'nama_proyek'];

    // log configuration
    protected static $logAttributes = ['nama_proyek', 'jumlah'];
    protected static $igonoreChangedAttributes = ['updated_at'];
    protected static $recordEvents = ['created', 'updated', 'deleted'];
    protected static $logOnlyDirty = true;
    protected static $logName = 'proyek';

    public function getDescriptionForEvent(string $eventName): string
    {
        return "You have {$eventName} proyek";
    }

    /**
     * Get the user that owns the Proyek
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get the barang that owns the Proyek
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function barang(): BelongsTo
    {
        return $this->belongsTo(Barang::class, 'barang_id');
    }
}
