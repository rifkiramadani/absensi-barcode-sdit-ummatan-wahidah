<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        $setting = Setting::firstOrCreate(
            ['id' => 1],
            [
                'max_time'         => '07:00:00',
                'teacher_max_time' => '07:15:00',
            ]
        );

        return view('settings.index', compact('setting'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'max_time'         => 'required',
            'teacher_max_time' => 'required',
        ]);

        $setting = Setting::find(1);
        $setting->update([
            'max_time'         => $request->max_time,
            'teacher_max_time' => $request->teacher_max_time,
        ]);

        return redirect()->back()->with('success', 'Pengaturan waktu berhasil diperbarui!');
    }
}
