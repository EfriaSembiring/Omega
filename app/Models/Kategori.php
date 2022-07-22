<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class Kategori extends Model
{
    use HasFactory, LogsActivity;

    protected $table = 'kategori';
    protected $fillable = ['nama'];

    // log configuration
    protected static $logAttributes = ['nama'];
    protected static $igonoreChangedAttributes = ['updated_at'];
    protected static $recordEvents = ['created', 'updated', 'deleted'];
    protected static $logOnlyDirty = true;
    protected static $logName = 'kategori';

    public function getDescriptionForEvent(string $eventName): string
    {
        return "You have {$eventName} kategori";
    }
}
