<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Faculty;
use App\Models\Department;
use App\Models\ExamSlot;
use App\Models\Registration;

class RegistrationController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        $registrations = Registration::with(['department.faculty', 'examSlot'])
            ->when($search, function ($query, $search) {
                $query->where('registered_by', 'like', "%{$search}%")
                    ->orWhereHas('department.faculty', function ($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%");
                    });
            })
            ->get();

        // Calculate remaining capacity for each exam slot
        $examSlots = ExamSlot::orderBy('exam_date', 'asc')->orderBy('start_time', 'asc')->get()->map(function ($slot) {
            $totalStudents = Registration::where('exam_slot_id', $slot->id)
                ->with('department')
                ->get()
                ->sum(function ($registration) {
                    return $registration->department->student_count;
                });

            $remainingCapacity = max(0, $slot->max_capacity - $totalStudents);

            return (object)[
                'id' => $slot->id,
                'exam_date' => $slot->exam_date,
                'start_time' => $slot->start_time,
                'end_time' => $slot->end_time,
                'max_capacity' => $slot->max_capacity,
                'remaining_capacity' => $remainingCapacity,
                'total_registered_students' => $totalStudents,
            ];
        });

        $faculties = Faculty::all();

        $summaryPerSlot = Registration::selectRaw('exam_slot_id, COUNT(*) as total')
            ->with('examSlot')
            ->groupBy('exam_slot_id')
            ->get();

        return view('register', [
            'registrations' => $registrations,
            'faculties' => $faculties,
            'examSlots' => $examSlots,
            'summaryPerSlot' => $summaryPerSlot,
        ]);
    }

    // New method: list registrations with filters for API
    public function listRegistrations(Request $request)
    {
        $search = $request->input('search');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $facultyId = $request->input('faculty_id');

        $query = Registration::with(['department.faculty', 'examSlot']);

        if ($search) {
            $query->where('registered_by', 'like', "%{$search}%");
        }

        if ($startDate) {
            $query->whereHas('examSlot', function ($q) use ($startDate) {
                $q->where('exam_date', '>=', $startDate);
            });
        }

        if ($endDate) {
            $query->whereHas('examSlot', function ($q) use ($endDate) {
                $q->where('exam_date', '<=', $endDate);
            });
        }

        if ($facultyId && $facultyId !== 'all') {
            $query->whereHas('department.faculty', function ($q) use ($facultyId) {
                $q->where('id', $facultyId);
            });
        }

        $registrations = $query->get();

        return response()->json($registrations);
    }

    // Export methods
    public function exportExcel()
    {
        return \Excel::download(new \App\Exports\RegistrationsExport, 'registrations.xlsx');
    }

    public function exportPDF()
    {
        $registrations = Registration::with(['department.faculty', 'examSlot'])->get();
        $pdf = \PDF::loadView('exports.registrations_pdf', compact('registrations'));
        return $pdf->download('registrations.pdf');
    }

    public function exportCSV()
    {
        return \Excel::download(new \App\Exports\RegistrationsExport, 'registrations.csv', \Maatwebsite\Excel\Excel::CSV);
    }

    public function getDepartments($faculty_id)
    {
        $examSlotId = request()->query('exam_slot_id');
        $registeredByName = request()->query('registered_by');

        $query = Department::where('faculty_id', $faculty_id);

        if ($registeredByName) {
            // Get departments registered by this user in any slot
            $userRegisteredDeptIds = Registration::where('registered_by', $registeredByName)
                ->pluck('department_id')
                ->toArray();

            if ($examSlotId) {
                // Get departments already registered in this slot
                $registeredDeptIds = Registration::where('exam_slot_id', $examSlotId)
                    ->pluck('department_id')
                    ->toArray();
            } else {
                $registeredDeptIds = [];
            }

            return $query->get()->map(function ($dept) use ($registeredDeptIds, $userRegisteredDeptIds) {
                $dept->disabled = in_array($dept->id, $registeredDeptIds);
                $dept->registered_in_other_slot = in_array($dept->id, $userRegisteredDeptIds) && !$dept->disabled;
                return $dept;
            });
        }

        return $query->get();
    }

    public function store(Request $request)
    {
        $request->validate([
            'faculty_id' => 'required|exists:faculties,id',
            'department_ids' => 'required|array',
            'exam_slot_id' => 'required|exists:exam_slots,id',
            'registered_by' => 'required|string|max:255',
        ]);

        $slot = ExamSlot::find($request->exam_slot_id);
        
        // Calculate total students being registered
        $totalStudents = Department::whereIn('id', $request->department_ids)
            ->sum('student_count');

        // Check slot capacity
        $registeredStudents = Registration::where('exam_slot_id', $slot->id)
            ->with('department')
            ->get()
            ->sum(function ($registration) {
                return $registration->department->student_count;
            });

        if (($registeredStudents + $totalStudents) > $slot->max_capacity) {
            return back()->with('error', 'จำนวนนักศึกษาที่เลือกเกินความจุของวันสอบนี้');
        }

        // Check if departments are already registered in this slot
        $existingDeptIds = Registration::where('exam_slot_id', $slot->id)
            ->whereIn('department_id', $request->department_ids)
            ->pluck('department_id')
            ->toArray();

        if (!empty($existingDeptIds)) {
            return back()->with('error', 'มีบางสาขาที่ถูกลงทะเบียนในวันนี้แล้ว');
        }

        // Check if departments are already registered by this user in other slots
        $userRegisteredDeptIds = Registration::where('registered_by', $request->registered_by)
            ->whereIn('department_id', $request->department_ids)
            ->pluck('department_id')
            ->toArray();

        if (!empty($userRegisteredDeptIds)) {
            return back()->with('error', 'คุณได้ลงทะเบียนบางสาขาในวันอื่นแล้ว');
        }

        // Create registrations
        foreach ($request->department_ids as $deptId) {
            Registration::create([
                'department_id' => $deptId,
                'exam_slot_id' => $slot->id,
                'registered_by' => $request->registered_by,
            ]);
        }

        return back()->with('success', 'ลงทะเบียนสำเร็จแล้ว');
    }

    public function update(Request $request, $id)
    {
        $registration = Registration::findOrFail($id);

        $request->validate([
            'department_id' => 'required|exists:departments,id',
            'exam_slot_id' => 'required|exists:exam_slots,id',
            'registered_by' => 'required|string|max:255',
        ]);

        $registration->update([
            'department_id' => $request->department_id,
            'exam_slot_id' => $request->exam_slot_id,
            'registered_by' => $request->registered_by,
        ]);

        return response()->json(['success' => true, 'message' => 'แก้ไขการลงทะเบียนสำเร็จ']);
    }

    public function destroy($id)
    {
        $registration = Registration::findOrFail($id);
        $registration->delete();

        return response()->json(['success' => true, 'message' => 'ลบการลงทะเบียนสำเร็จ']);
    }

    public function report()
    {
        $registrations = Registration::with(['department.faculty', 'examSlot'])
            ->join('exam_slots', 'registrations.exam_slot_id', '=', 'exam_slots.id')
            ->orderBy('exam_slots.exam_date', 'asc')
            ->orderBy('exam_slots.start_time', 'asc')
            ->select('registrations.*')
            ->get();

        $groupedByDate = $registrations->groupBy(function ($item) {
            return $item->examSlot->exam_date;
        });

        $faculties = Faculty::all();
        $examSlots = ExamSlot::orderBy('exam_date', 'asc')->orderBy('start_time', 'asc')->get();

        // Calculate total students registered across all exam slots
        $totalStudentsRegistered = $registrations->sum(function ($registration) {
            return $registration->department->student_count;
        });

        return view('admin.registrations_report', [
            'registrations' => $registrations,
            'groupedByDate' => $groupedByDate,
            'faculties' => $faculties,
            'examSlots' => $examSlots,
            'totalStudentsRegistered' => $totalStudentsRegistered,
        ]);
    }

    // New method for admin backend registration check page
    public function adminRegistrations(Request $request)
    {
        // This method has been moved to AdminRegistrationController
        abort(404);
    }
}
