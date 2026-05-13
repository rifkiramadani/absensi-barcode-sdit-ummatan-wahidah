<?php

namespace App\Http\Controllers;

use App\Exports\TeacherAttendanceExport;
use App\Http\Controllers\Controller;
use App\Models\Teacher;
use App\Models\TeacherAttendance;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class TeacherAttendanceController extends Controller
{
    public function recap(Request $request)
    {
        $filter     = $request->get('filter', 'daily');
        $search     = $request->get('search');
        $startDate  = $request->get('start_date');
        $endDate    = $request->get('end_date');
        $keterangan = $request->get('keterangan');

        $query = TeacherAttendance::with('teacher')->latest('date');

        if ($search) {
            $query->whereHas('teacher', function ($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('nip', 'LIKE', "%{$search}%");
            });
        }

        if ($keterangan) {
            if ($keterangan === 'Tidak Hadir') {
                $query->whereNotNull('keterangan');
            } elseif ($keterangan === 'Hadir') {
                $query->whereIn('status', ['Hadir', 'Telat'])->whereNull('keterangan');
            } else {
                $query->where('keterangan', $keterangan);
            }
        }

        if ($startDate && $endDate) {
            $query->whereBetween('date', [$startDate, $endDate]);
        } else {
            $date = Carbon::today();
            switch ($filter) {
                case 'weekly':
                    $query->whereBetween('date', [
                        $date->startOfWeek()->toImmutable(),
                        $date->endOfWeek()->toImmutable()
                    ]);
                    break;
                case 'monthly':
                    $query->whereMonth('date', $date->month)->whereYear('date', $date->year);
                    break;
                case 'yearly':
                    $query->whereYear('date', $date->year);
                    break;
                default:
                    // Hanya batasi ke hari ini jika tidak ada filter apapun
                    if (!$search && !$keterangan) {
                        $query->whereDate('date', Carbon::today());
                    }
                    break;
            }
        }

        $attendances = $query->paginate(10)->withQueryString();

        $guruList = Teacher::where('is_active', true)
                    ->orderBy('name')
                    ->get()
                    ->map(fn($g) => [
                        'id'      => $g->id,
                        'name'    => $g->name,
                        'jabatan' => $g->jabatan ?? '-',
                    ])
                    ->values();

        return view('teacher_attendances.recap', compact(
            'attendances', 'filter', 'search', 'startDate', 'endDate', 'guruList', 'keterangan'
        ));
    }

    public function storeKeterangan(Request $request)
    {
        $request->validate([
            'teacher_id'         => 'required|exists:teachers,id',
            'date'               => 'required|date',
            'keterangan'         => 'required|in:Izin,Sakit,Alpa',
            'catatan_keterangan' => 'nullable|string|max:255',
        ]);

        $existing = TeacherAttendance::where('teacher_id', $request->teacher_id)
                                     ->where('date', $request->date)
                                     ->first();

        if ($existing) {
            $existing->update([
                'keterangan'         => $request->keterangan,
                'catatan_keterangan' => $request->catatan_keterangan,
            ]);
        } else {
            TeacherAttendance::create([
                'teacher_id'         => $request->teacher_id,
                'date'               => $request->date,
                'keterangan'         => $request->keterangan,
                'catatan_keterangan' => $request->catatan_keterangan,
            ]);
        }

        // Redirect ke filter monthly agar data langsung terlihat
        return redirect()->route('teacher_attendance.recap', ['filter' => 'monthly'])
            ->with('success', 'Keterangan guru berhasil disimpan!');
    }

    public function exportExcel(Request $request)
    {
        $filter    = $request->get('filter', 'daily');
        $startDate = $request->get('start_date');
        $endDate   = $request->get('end_date');

        $fileName = 'rekap_absensi_guru_' . now()->format('Y-m-d_His') . '.xlsx';
        return Excel::download(new TeacherAttendanceExport($filter, $startDate, $endDate), $fileName);
    }

    public function destroy(TeacherAttendance $teacherAttendance)
    {
        $teacherAttendance->delete();
        return back()->with('success', 'Data absensi guru berhasil dihapus.');
    }
}
