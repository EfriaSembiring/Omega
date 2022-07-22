<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class ProyekBarang extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = ['proyek_id', 'barang_id', 'jumlah'];

    // log configuration
    protected static $logAttributes = ['proyek_id', 'barang_id', 'jumlah'];
    protected static $ignoreChangedAttributes = ['updated_at'];
    protected static $recordEvents = ['created', 'updated'];
    protected static $logOnlyDirty = true;
    protected static $logName = 'proyek-barang';

    public function proyek()
    {
    	return $this->belongsTo(Proyek::class);
    }

    public function barang()
    {
    	return $this->belongsTo(Barang::class);
    }
}
