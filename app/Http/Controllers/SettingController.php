<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
   public function index()
    {
        // Ambil data pertama, jika tidak ada buat baru dengan default
        $setting = Setting::firstOrCreate(
            ['id' => 1],
            ['max_time' => '07:30:00']
        );

        return view('settings.index', compact('setting'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'max_time' => 'required',
        ]);

        $setting = Setting::find(1);
        $setting->update([
            'max_time' => $request->max_time
        ]);

        return redirect()->back()->with('success', 'Pengaturan waktu berhasil diperbarui!');
    }
}
