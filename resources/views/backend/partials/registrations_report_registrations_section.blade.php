<div id="registrations-section" class="dashboard-card mb-4">
    <div class="card-header d-flex justify-content-between align-items-center">
        <div>
            <h4 class="mb-0">
                <i class="bi bi-list-check"></i> 
                การลงทะเบียนทั้งหมด
            </h4>
            <small class="opacity-75 d-block mt-1">รายการลงทะเบียนสอบทั้งหมด</small>
    </div>
</div>
    
    <div class="card-body p-4">
    <!-- Search and Filter Section -->
    <h4 class="mb-4"><i class="bi bi-search"></i> ค้นหาการลงทะเบียน</h4>
    <form method="GET" action="{{ route('admin.registrations') }}">
        <div class="row g-3">
            <div class="col-md-4">
                <label for="search" class="form-label">ชื่อผู้ลงทะเบียน</label>
                <input type="text" name="search" id="search" class="form-control" placeholder="ค้นหาชื่อผู้ลงทะเบียน" value="{{ old('search', $search ?? '') }}">
            </div>
            <div class="col-md-3">
                <label for="faculty_id" class="form-label">คณะ</label>
                <select name="faculty_id" id="faculty_id" class="form-select">
                    <option value="all" {{ (isset($facultyId) && $facultyId === 'all') ? 'selected' : '' }}>ทุกคณะ</option>
                    @foreach ($faculties as $faculty)
                        <option value="{{ $faculty->id }}" {{ (isset($facultyId) && $facultyId == $faculty->id) ? 'selected' : '' }}>
                            {{ $faculty->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label for="exam_date" class="form-label">วันสอบ</label>
                <select name="exam_date" id="exam_date" class="form-select">
                    <option value="all">ทุกวันสอบ</option>
                    @foreach ($examSlots as $slot)
                        <option value="{{ $slot->exam_date }}">{{ $slot->exam_date }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2 d-flex align-items-end">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="bi bi-search"></i> ค้นหา
                </button>
            </div>
        </div>
    </form>
    <div class="dashboard-card mb-4">     
</div>

        <!-- Registrations by Date -->
        @forelse ($groupedByDate as $examDate => $dateRegistrations)
            @php
                $formattedDate = \App\Helpers\DateHelper::formatThaiDate($examDate);
                $dateSlots = $dateRegistrations->groupBy(function ($item) {
                    return $item->examSlot->start_time . ' - ' . $item->examSlot->end_time;
                });
                $totalStudentsDate = $dateRegistrations->sum(fn($r) => $r->department->student_count ?? 0);
            @endphp
            
            <!-- Date Card -->
            <div class="dashboard-card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="mb-0">
                            <i class="bi bi-calendar-date me-2"></i>
                            {{ $formattedDate }}
                        </h5>
                        <small class="text-muted">รวม {{ $totalStudentsDate }} นักศึกษา</small>
                    </div>
                    <span class="badge bg-primary">
                        <i class="bi bi-clock-history me-1"></i>
                        {{ $dateSlots->count() }} ช่วงเวลา
                    </span>
                </div>
                
                <div class="card-body p-0">
                    @foreach ($dateSlots as $timeSlot => $registrationsGroup)
                        @php
                            $timeParts = explode('-', $timeSlot);
                            $startTime = \Carbon\Carbon::parse(trim($timeParts[0]))->format('H:i');
                            $endTime = \Carbon\Carbon::parse(trim($timeParts[1]))->format('H:i');
                            $timePart = $startTime . ' - ' . $endTime;
                            $totalStudents = $registrationsGroup->sum(fn($r) => $r->department->student_count ?? 0);
                        @endphp
                        
                        <!-- Time Slot Section -->
                        <div class="time-slot-section border-bottom">
                            <div class="p-3 bg-light d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-0 text-primary">
                                        <i class="bi bi-clock me-2"></i>
                                        {{ $timePart }}
                                    </h6>
                                <small class="text-muted">{{ $registrationsGroup->count() }} รายการ, <strong>จำนวนรวมของนักศึกษาที่ลงทะเบียน: {{ $totalStudents }} คน</strong></small>
                                </div>
                                <button class="btn btn-sm btn-outline-primary toggle-registrations" data-target="registrations-{{ $examDate }}-{{ $startTime }}{{ $endTime }}">
                                    <i class="bi bi-chevron-down"></i>
                                </button>
                            </div>
                            
                            <!-- Registrations Table (Collapsible) -->
                            <div class="registrations-table" id="registrations-{{ $examDate }}-{{ $startTime }}{{ $endTime }}">
                                <div class="table-responsive">
                                    <table class="table table-hover align-middle mb-0">
                                        <thead class="table-light">
                                            <tr>
                                                <th width="50">#</th>
                                                <th>ผู้ลงทะเบียน</th>
                                                <th>คณะ</th>
                                                <th>สาขา</th>
                                                <th class="text-center">จำนวน</th>
                                                <th width="120">ดำเนินการ</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($registrationsGroup as $index => $registration)
                                                <tr>
                                                    <td class="text-center">{{ $index + 1 }}</td>
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <div class="user-avatar me-3">
                                                                {{ mb_substr($registration->registered_by, 0, 1) }}
                                                            </div>
                                                            <div>
                                                                <div class="fw-bold">{{ $registration->registered_by }}</div>
                                                                <small class="text-muted">{{ $registration->created_at->format('d/m/Y H:i') }}</small>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>{{ $registration->department->faculty->name ?? '-' }}</td>
                                                    <td>
                                                        <span class="badge bg-info bg-opacity-10 text-info">
                                                            {{ $registration->department->name ?? '-' }}
                                                        </span>
                                                    </td>
                                                    <td class="text-center">
                                                        <span class="badge bg-primary rounded-pill px-3">
                                                            {{ $registration->department->student_count ?? 0 }}
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <div class="d-flex gap-2">
                                                            <button class="btn btn-sm btn-outline-primary" 
                                                                    onclick="editRegistration({{ $registration->id }})"
                                                                    title="แก้ไข">
                                                                <i class="bi bi-pencil"></i>
                                                            </button>
                                                            <button class="btn btn-sm btn-outline-danger" 
                                                                    onclick="deleteRegistration({{ $registration->id }})"
                                                                    title="ลบ">
                                                                <i class="bi bi-trash"></i>
                                                            </button>
                                                            <a href="#" class="btn btn-sm btn-outline-secondary" title="ดูรายละเอียด">
                                                                <i class="bi bi-eye"></i>
                                                            </a>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @empty
            <div class="empty-state text-center py-5">
                <i class="bi bi-calendar-x text-muted" style="font-size: 3rem;"></i>
                <h5 class="mt-3 text-muted">ไม่พบข้อมูลการลงทะเบียน</h5>
                <p class="text-muted">ยังไม่มีผู้ลงทะเบียนสอบในระบบ</p>
                <button class="btn btn-primary mt-3" id="btnAddRegistrationEmpty">
                    <i class="bi bi-plus-circle me-2"></i> เพิ่มการลงทะเบียน
                </button>
            </div>
        @endforelse
    </div>
    <!-- Pagination -->
    <div class="d-flex justify-content-center mt-4">
        {{ $registrations->appends(request()->except('page_registrations'))->fragment('registrations-section')->links('pagination::bootstrap-5') }}
    </div> 
</div>

<script>
    // Toggle registration tables
    document.querySelectorAll('.toggle-registrations').forEach(button => {
        if (!button) return;
        button.addEventListener('click', function() {
            const target = document.getElementById(this.getAttribute('data-target'));
            if (!target) return;
            const icon = this.querySelector('i');
            if (!icon) return;
            
            if (target.style.display === 'none') {
                target.style.display = 'block';
                icon.classList.remove('bi-chevron-right');
                icon.classList.add('bi-chevron-down');
            } else {
                target.style.display = 'none';
                icon.classList.remove('bi-chevron-down');
                icon.classList.add('bi-chevron-right');
            }
        });
    });

    // Initialize all tables as collapsed
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.registrations-table').forEach(table => {
            if (!table) return;
            table.style.display = 'none';
        });
    });

    // Filter functionality
    const applyFiltersBtn = document.getElementById('apply-filters');
    if (applyFiltersBtn) {
        applyFiltersBtn.addEventListener('click', function() {
            const searchInput = document.getElementById('registration-search');
            const facultyFilter = document.getElementById('faculty-filter');
            const dateFilter = document.getElementById('date-filter');
            if (!searchInput || !facultyFilter || !dateFilter) return;

            const searchTerm = searchInput.value.toLowerCase();
            const facultyId = facultyFilter.value;
            const dateVal = dateFilter.value;
            
            // Implement your filtering logic here
            // This would typically involve AJAX calls to reload filtered data
            console.log('Applying filters:', {searchTerm, facultyId, dateVal});
        });
    }
</script>
