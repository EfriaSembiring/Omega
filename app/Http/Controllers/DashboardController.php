<?php

namespace App\Http\Controllers;

use App\Models\{Supplier, Barang, BarangKeluar, BarangMasuk, Laporan, Gudang};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Activitylog\Models\Activity;
use App\Http\Requests\SettingRequest;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
    * Show dashboard
    *
    * @return \Illuminate\Http\Response
    */
    public function index()
    {
        
                     
        
        $supplier = Supplier::count();
        $jumlah_barang = Barang::count();
        $barangs=Barang::get();
        $jumlah_gudang = Gudang::count();
        $data = Laporan::latest()->limit(6)->get();
        $date = date('Y-m-d');

        $barang_masuks = BarangMasuk::count();
        $barang_keluars = BarangKeluar::count();

        // untuk statistik perbulan
        // masuk 
         $masuk_jan = BarangMasuk::whereMonth('tgl_brg_masuk', '01')->count();
         $masuk_feb = BarangMasuk::whereMonth('tgl_brg_masuk', '02')->count();
         $masuk_mar = BarangMasuk::whereMonth('tgl_brg_masuk', '03')->count();
         $masuk_apr = BarangMasuk::whereMonth('tgl_brg_masuk', '04')->count();
         $masuk_mei = BarangMasuk::whereMonth('tgl_brg_masuk', '05')->count();
         $masuk_jun = BarangMasuk::whereMonth('tgl_brg_masuk', '06')->count();
         $masuk_jul = BarangMasuk::whereMonth('tgl_brg_masuk', '07')->count();
         $masuk_agu = BarangMasuk::whereMonth('tgl_brg_masuk', '08')->count();
         $masuk_sep = BarangMasuk::whereMonth('tgl_brg_masuk', '09')->count();
         $masuk_okt = BarangMasuk::whereMonth('tgl_brg_masuk', '10')->count();
         $masuk_nov = BarangMasuk::whereMonth('tgl_brg_masuk', '11')->count();
         $masuk_des = BarangMasuk::whereMonth('tgl_brg_masuk', '12')->count();

         // keluar
         $keluar_jan = BarangKeluar::whereMonth('tgl_brg_keluar', '01')->count();
         $keluar_feb = BarangKeluar::whereMonth('tgl_brg_keluar', '02')->count();
         $keluar_mar = BarangKeluar::whereMonth('tgl_brg_keluar', '03')->count();
         $keluar_apr = BarangKeluar::whereMonth('tgl_brg_keluar', '04')->count();
         $keluar_mei = BarangKeluar::whereMonth('tgl_brg_keluar', '05')->count();
         $keluar_jun = BarangKeluar::whereMonth('tgl_brg_keluar', '06')->count();
         $keluar_jul = BarangKeluar::whereMonth('tgl_brg_keluar', '07')->count();
         $keluar_agu = BarangKeluar::whereMonth('tgl_brg_keluar', '08')->count();
         $keluar_sep = BarangKeluar::whereMonth('tgl_brg_keluar', '09')->count();
         $keluar_okt = BarangKeluar::whereMonth('tgl_brg_keluar', '10')->count();
         $keluar_nov = BarangKeluar::whereMonth('tgl_brg_keluar', '11')->count();
         $keluar_des = BarangKeluar::whereMonth('tgl_brg_keluar', '12')->count();





        return view('admin.dashboard',
        compact('supplier', 'barangs', 
        'jumlah_gudang', 'jumlah_barang', 'data', 'barang_masuks', 'barang_keluars','masuk_jan',
        'masuk_feb','masuk_mar','masuk_apr','masuk_mei', 'masuk_jun','masuk_jul','masuk_agu',
        'masuk_sep', 'masuk_okt', 'masuk_nov','masuk_des', 
        'keluar_jan', 'keluar_feb','keluar_mar','keluar_apr','keluar_mei', 'keluar_jun','keluar_jul','keluar_agu',
        'keluar_sep', 'keluar_okt', 'keluar_nov','keluar_des'));

            
            
        
    }

    /**
    * Show activity logs
    *
    * @return \Illuminate\Http\Response
    */
    public function activity_logs()
    {
        if(auth()->user()->getRoleNames()[0] == "Admin") {
            $logs = Activity::with('causer')->latest()->paginate(10);
        } else {
            $logs = Activity::with('causer')->where('causer_id', auth()->id())->latest()->paginate(10);
        }
        // dd($logs->first()->causer->username);
        // dd($logs);

        return view('admin.logs', compact('logs'));
    }

	/**
	* Store settings into database
	*
	* @param $request
    * @return \Illuminate\Http\Response
	*/
    public function settings_store(SettingRequest $request)
    {
    	// when you upload a logo image
    	if($request->file('logo')) {
	    	$filename = $request->file('logo')->getClientOriginalName();
	    	$filePath = $request->file('logo')->storeAs('uploads', $filename, 'public');
	    	setting()->set('logo', $filePath);
    	}

    	setting()->set('site_name', $request->site_name);
    	setting()->set('keyword', $request->keyword);
    	setting()->set('description', $request->description);
    	setting()->set('url', $request->url);

    	// save all
    	setting()->save();
    	return redirect()->back()->with('success', 'Settings has been successfully saved');
    }

    /**
    * Update profile user
    *
    * @param $request
    * @return \Illuminate\Http\Response
    */
    public function profile_update(Request $request)
    {
        $data = ['name' => $request->name];

        // if password want to change
        if($request->old_password && $request->new_password) {
            // verify if password is match
            if(!Hash::check($request->old_password, auth()->user()->password)) {
                session()->flash('failed', 'Password is wrong!');
                return redirect()->back();
            }

            $data['password'] = Hash::make($request->new_password);
        } 

        // for update avatar
        if($request->avatar) {
            $data['avatar'] = $request->avatar;

            if(auth()->user()->avatar) {
                unlink(storage_path('app/public/'.auth()->user()->avatar));
            }
        }
        
        // update profile
        auth()->user()->update($data);
        
        return redirect()->back()->with('success', 'Profile updated!');
    }

    /**
    * Store avatar images into database
    *
    * @param $request
    * @return string
    */
    public function upload_avatar(Request $request)
    {
        $request->validate(['avatar'  => 'file|image|mimes:jpg,png,svg|max:1024']);

        if($request->hasFile('avatar')){
            $file = $request->file('avatar');

            $fileName = $file->getClientOriginalName();
            $folder = 'user-'.auth()->id();

            $file->storeAs('avatars/'.$folder, $fileName, 'public');

            return 'avatars/'.$folder.'/'.$fileName;
        }

        return '';
        
    }

    public function delete_logs()
    {
        $logs = Activity::where('created_at', '<=', Carbon::now()->subWeeks())->delete();

        return back()->with('success', $logs.' Logs successfully deleted!');
    }
}
