<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\URL;
use Spatie\Activitylog\Traits\LogsActivity;

class BarangKeluar extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = ['barang_id', 'jumlah', 'proyek_id','tgl_brg_keluar'];

    // log configuration
    protected static $logAttributes = ['jumlah', 'tgl_brg_keluar'];
    protected static $igonoreChangedAttributes = ['updated_at'];
    protected static $recordEvents = ['created', 'updated', 'deleted'];
    protected static $logOnlyDirty = true;
    protected static $logName = 'goods';

    public function getDescriptionForEvent(string $eventName): string
    {
        return "You have {$eventName} stock-out";
    }

    public function barang()
    {
    	return $this->belongsTo(Barang::class);
    }

    public function proyek()
    {
        return $this->belongsTo(Proyek::class);
    }
}
