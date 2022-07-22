<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\URL;
use Spatie\Activitylog\Traits\LogsActivity;

class Barang extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = ['kode', 'nama', 'jumlah', 'gudang_id', 'kategori_id','merk', 'warna', 'satuan', 'image'];

    // log configuration
    protected static $logAttributes = ['kode', 'nama', 'jumlah', 'merk', 'warna', 'satuan'];
    protected static $igonoreChangedAttributes = ['updated_at'];
    protected static $recordEvents = ['created', 'updated', 'deleted'];
    protected static $logOnlyDirty = true;
    protected static $logName = 'goods';

    public function getDescriptionForEvent(string $eventName): string
    {
        return "You have {$eventName} goods";
    }


    public function getImageAttribute()
    {
        return $this->attributes['image'] ?  URL::to('/') . '/uploads/barang/' . $this->attributes['image'] : null;
    }

    public function barang_masuks()
    {
    	return $this->hasMany(BarangMasuk::class);
    }

    public function gudang()
    {
    	return $this->belongsTo(Gudang::class);
    }

    public function kategori()
    {
    	return $this->belongsTo(Kategori::class);
    }
}
