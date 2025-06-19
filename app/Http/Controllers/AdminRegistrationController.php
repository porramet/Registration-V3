<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Faculty;
use App\Models\ExamSlot;
use App\Models\Registration;
use App\Models\Department;

class AdminRegistrationController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $facultyId = $request->input('faculty_id');

        $query = Registration::with(['department.faculty', 'examSlot'])
            ->join('exam_slots', 'registrations.exam_slot_id', '=', 'exam_slots.id')
            ->select('registrations.*');

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

        $registrations = $query->orderBy('exam_slots.exam_date')->orderBy('exam_slots.start_time')->paginate(24, ['*'], 'page_registrations');

        $faculties = Faculty::with('departments')->paginate(24, ['*'], 'page_faculties');
        $examSlots = ExamSlot::orderBy('exam_date', 'asc')->orderBy('start_time', 'asc')->paginate(12, ['*'], 'page_exams');

        // Calculate quick stats counts
        $totalRegisteredStudents = Registration::with('department')->get()->sum(function ($registration) {
            return $registration->department->student_count ?? 0;
        });
        $totalStudents = Department::sum('student_count');
        $totalFaculties = Faculty::count();
        $totalDepartments = Department::count();
        $totalExamDates = ExamSlot::distinct('exam_date')->count('exam_date');

        // Calculate total students per exam slot
        $summaryPerSlot = Registration::selectRaw('exam_slots.id as exam_slot_id, SUM(departments.student_count) as total_students')
            ->join('exam_slots', 'registrations.exam_slot_id', '=', 'exam_slots.id')
            ->join('departments', 'registrations.department_id', '=', 'departments.id')
            ->groupBy('exam_slots.id')
            ->orderBy('exam_slots.exam_date')
            ->orderBy('exam_slots.start_time')
            ->get();

        // Create a list of all registered department IDs (regardless of exam slot)
        $registeredDepartmentIds = Registration::distinct('department_id')
            ->pluck('department_id')
            ->toArray();

        // Create a map of exam_slot_id => total_students for quick lookup
        $totalStudentsMap = $summaryPerSlot->pluck('total_students', 'exam_slot_id')->toArray();

        // Fetch departments for all faculties to avoid undefined variable error in view
        $departments = Department::all();

        // Group registrations by exam date for the new view structure
        $groupedByDate = $registrations->groupBy(function ($item) {
            return $item->examSlot->exam_date;
        });

        $registrationsByExamSlot = $registrations->groupBy('exam_slot_id');

        return view('backend.registrations_report', [
            'registrations' => $registrations,
            'groupedByDate' => $groupedByDate,
            'registrationsByExamSlot' => $registrationsByExamSlot,
            'faculties' => $faculties,
            'departments' => $departments,
            'examSlots' => $examSlots,
            'examSlotsArray' => $examSlots->items(),
            'totalStudentsMap' => $totalStudentsMap,
            'totalRegisteredStudents' => $totalRegisteredStudents,
            'totalStudents' => $totalStudents,
            'totalFaculties' => $totalFaculties,
            'totalDepartments' => $totalDepartments,
            'totalExamDates' => $totalExamDates,
            'search' => $search,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'facultyId' => $facultyId,
            'registeredDepartmentIds' => $registeredDepartmentIds,
        ]);
    }

    public function updateExamSlot(Request $request, $id)
    {
        \Log::info('updateExamSlot called', ['id' => $id, 'request' => $request->all()]);

        $request->validate([
            'exam_date' => 'nullable|date',
            'start_time' => ['nullable', 'regex:/^\d{2}:\d{2}(:\d{2})?$/'],
            'end_time' => ['nullable', 'regex:/^\d{2}:\d{2}(:\d{2})?$/'],
            'max_capacity' => 'nullable|integer|min:1',
        ]);

        // Custom validation: if both start_time and end_time are present, end_time must be after start_time
        if ($request->filled('start_time') && $request->filled('end_time')) {
            if (strtotime($request->end_time) <= strtotime($request->start_time)) {
                return redirect()->back()->withErrors(['end_time' => 'เวลาสิ้นสุดต้องมากกว่าเวลาเริ่มต้น'])->withInput();
            }
        }

        $examSlot = ExamSlot::findOrFail($id);

        \Log::info('Before update', ['examSlot' => $examSlot->toArray(), 'dataToUpdate' => $request->all()]);

        $dataToUpdate = [];
        if ($request->filled('exam_date')) {
            $dataToUpdate['exam_date'] = $request->exam_date;
        }
        if ($request->filled('start_time')) {
            $dataToUpdate['start_time'] = date('H:i:s', strtotime($request->start_time));
        } else {
            $dataToUpdate['start_time'] = null;
        }
        if ($request->filled('end_time')) {
            $dataToUpdate['end_time'] = date('H:i:s', strtotime($request->end_time));
        } else {
            $dataToUpdate['end_time'] = null;
        }
        if ($request->filled('max_capacity')) {
            $dataToUpdate['max_capacity'] = $request->max_capacity;
        }

        \Log::info('Data to update', ['dataToUpdate' => $dataToUpdate]);

        if (!empty($dataToUpdate)) {
            $examSlot->fill($dataToUpdate);
            try {
                $examSlot->save();
                \Log::info('ExamSlot saved successfully', ['examSlot' => $examSlot->toArray()]);
            } catch (\Exception $e) {
                \Log::error('Error saving ExamSlot', ['error' => $e->getMessage()]);
                return redirect()->back()->with('error', 'เกิดข้อผิดพลาดในการบันทึกข้อมูล')->withInput();
            }
        }

        \Log::info('After update', ['examSlot' => $examSlot->toArray()]);

        \Log::info('updateExamSlot completed', ['examSlot' => $examSlot->toArray()]);

        return redirect()->route('admin.registrations')->with('success', 'แก้ไขวันสอบสำเร็จ');
    }


    public function store(Request $request)
    {
        $request->validate([
            'department_id' => 'required|exists:departments,id',
            'exam_slot_id' => 'required|exists:exam_slots,id',
            'registered_by' => 'required|string|max:255',
        ]);

        $slot = ExamSlot::find($request->exam_slot_id);

        // Calculate total students being registered
        $totalStudents = Department::where('id', $request->department_id)->value('student_count');

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

        // Check if department is already registered anywhere
        $existingDept = Registration::where('department_id', $request->department_id)->first();

        if ($existingDept) {
            return back()->with('error', 'สาขานี้ถูกลงทะเบียนแล้ว ไม่สามารถเลือกซ้ำได้');
        }

        // Create registration
        Registration::create([
            'department_id' => $request->department_id,
            'exam_slot_id' => $slot->id,
            'registered_by' => $request->registered_by,
        ]);

        return back()->with('success', 'ลงทะเบียนสำเร็จแล้ว');
    }

    public function update(Request $request, $id)
    {
        \Log::info('AdminRegistrationController@update called', ['id' => $id, 'request' => $request->all()]);

        $registration = Registration::findOrFail($id);

        $request->validate([
            'department_id' => 'required|exists:departments,id',
            'exam_slot_id' => 'required|exists:exam_slots,id',
            'registered_by' => 'required|string|max:255',
        ]);

        // Check if the new department is already registered anywhere (excluding current registration)
        $existingRegistration = Registration::where('department_id', $request->department_id)
            ->where('id', '!=', $id)
            ->first();

        if ($existingRegistration) {
            \Log::warning('Duplicate department registration attempt', ['department_id' => $request->department_id]);
            return redirect()->back()->with('error', 'สาขานี้ได้ถูกลงทะเบียนแล้ว ไม่สามารถเลือกซ้ำได้');
        }

        $registration->update([
            'department_id' => $request->department_id,
            'exam_slot_id' => $request->exam_slot_id,
            'registered_by' => $request->registered_by,
        ]);

        \Log::info('Registration updated successfully', ['registration' => $registration->toArray()]);

        // Return redirect to admin registrations page after update
        return redirect()->route('admin.registrations')->with('success', 'แก้ไขการลงทะเบียนสำเร็จ');
    }

    public function destroy($id)
    {
        $registration = Registration::findOrFail($id);
        $registration->delete();

        return redirect()->back()->with('success', 'ลบการลงทะเบียนสำเร็จ');
    }

    public function show($id)
    {
        $registration = Registration::with('department.faculty')->findOrFail($id);
        return response()->json($registration);
    }

    // Exam Slot CRUD methods

    public function storeExamSlot(Request $request)
    {
        $request->validate([
            'exam_date' => 'required|date',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'max_capacity' => 'required|integer|min:1',
        ]);

        ExamSlot::create([
            'exam_date' => $request->exam_date,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'max_capacity' => $request->max_capacity,
        ]);

        return redirect()->route('admin.registrations')->with('success', 'เพิ่มวันสอบสำเร็จ');
    }



    public function destroyExamSlot($id)
    {
        $examSlot = ExamSlot::findOrFail($id);

        // Remove the check to allow deletion even if registrations exist
        // Optionally, delete related registrations if needed
        Registration::where('exam_slot_id', $examSlot->id)->delete();

        $examSlot->delete();

        return redirect()->route('admin.registrations')->with('success', 'ลบวันสอบสำเร็จ');
    }

}
