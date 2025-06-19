<div id="exams-section" class="dashboard-card mb-4">
    <div class="card-header d-flex justify-content-between align-items-center">
        <div>
            <h4 class="mb-0">
                <i class="bi bi-calendar2-range"></i> 
                วันสอบทั้งหมด
                <span class="badge bg-white text-primary ms-2">{{ $totalExamDates }} วัน</span>
            </h4>
            <small class="opacity-75 d-block mt-1">ตารางวันและเวลาสอบทั้งหมด</small>
        </div>
        <button class="btn btn-sm btn-primary" id="btnAddExamSlot">
            <i class="bi bi-plus-lg me-1"></i> เพิ่มวันสอบ
        </button>
    </div>
    
    <div class="card-body p-4">
        <!-- Search and Filter Section -->
        <div class="row mb-4 g-3">
            <div class="col-md-6">
                <div class="input-group">
                    <span class="input-group-text bg-white"><i class="bi bi-search"></i></span>
                    <input type="text" class="form-control" placeholder="ค้นหาวันสอบ..." id="exam-search">
                </div>
            </div>
            <div class="col-md-3">
                <select class="form-select" id="exam-month-filter">
                    <option value="">ทุกเดือน</option>
                    @foreach($months as $month)
                        <option value="{{ $month['value'] }}">{{ $month['name'] }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <select class="form-select" id="exam-status-filter">
                    <option value="">ทุกสถานะ</option>
                    <option value="available">ว่าง</option>
                    <option value="full">เต็ม</option>
                    <option value="upcoming">ยังไม่ถึงวันสอบ</option>
                    <option value="past">ผ่านไปแล้ว</option>
                </select>
            </div>
        </div>

        <!-- Exam Slots Grid -->
        <div class="row g-4" id="exam-slots-container">
            @foreach ($examSlots as $slot)
                @php
                    $formattedDate = \App\Helpers\DateHelper::formatThaiDate($slot['exam_date']);
                    $totalStudents = $totalStudentsMap[$slot['id']] ?? 0;
                    $capacityPercentage = $slot['max_capacity'] > 0 
                        ? min(100, round(($totalStudents / $slot['max_capacity']) * 100)) 
                        : 0;
                    
                    // Determine status
                    $today = now()->format('Y-m-d');
                    $slotDate = $slot['exam_date'];
                    $isPast = $slotDate < $today;
                    $isUpcoming = $slotDate > $today;
                    $isFull = $totalStudents >= $slot['max_capacity'];
                @endphp
                
                <div class="col-xl-3 col-lg-4 col-md-6 exam-slot-item"
                    data-date="{{ $slot['exam_date'] }}"
                    data-status="{{ $isPast ? 'past' : ($isFull ? 'full' : ($isUpcoming ? 'upcoming' : 'available')) }}">
                    <div class="exam-time-card h-100">
<div class="card-header-custom d-flex justify-content-between align-items-center
                            {{ $isPast ? 'bg-secondary' : ($isFull ? 'bg-danger' : ($isUpcoming ? 'bg-warning' : 'bg-success')) }}"
                            style="background-image: url('{{ asset('img/Digital-exam.png') }}'); background-size: cover; background-position: center; background-repeat: no-repeat;">
                            <div style="background-color: rgba(0, 0, 0, 0.5); padding: 0 10px; border-radius: 4px;">
                                <div class="exam-date text-white">
                                    <i class="bi bi-calendar2-week me-2"></i>
                                    {{ $formattedDate }}
                                </div>
                                <div class="exam-time text-white">
                                    {{ $slot['start_time'] }} - {{ $slot['end_time'] }}
                                </div>
                            </div>
                            <span class="badge bg-white 
                                {{ $isPast ? 'text-secondary' : ($isFull ? 'text-danger' : ($isUpcoming ? 'text-warning' : 'text-success')) }}">
                                {{ $isPast ? 'ผ่านไปแล้ว' : ($isFull ? 'เต็ม' : ($isUpcoming ? 'ยังไม่ถึง' : 'ว่าง')) }}
                            </span>
                        </div>
                        
                        <div class="card-body">
                            <!-- Capacity Progress Bar -->
                            <div class="mb-3">
                                <div class="d-flex justify-content-between mb-1">
                                    <small class="text-muted">จำนวนผู้ลงทะเบียน</small>
                                    <small class="text-muted">{{ $totalStudents }}/{{ $slot['max_capacity'] }}</small>
                                </div>
                                <div class="progress" style="height: 8px;">
                                    <div class="progress-bar 
                                        {{ $isFull ? 'bg-danger' : ($isPast ? 'bg-secondary' : 'bg-success') }}" 
                                        role="progressbar" 
                                        style="width: {{ $capacityPercentage }}%" 
                                        aria-valuenow="{{ $capacityPercentage }}" 
                                        aria-valuemin="0" 
                                        aria-valuemax="100">
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Additional Info -->
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <span class="badge bg-soft-primary">
                                    <i class="bi bi-people me-1"></i> {{ $totalStudents }} คน
                                </span>
                                <span class="badge bg-light text-dark">
                                    <i class="bi bi-door-open me-1"></i> {{ $slot['max_capacity'] }} ที่นั่ง
                                </span>
                            </div>
                            
                            <!-- Action Buttons -->
                            <div class="d-flex gap-2">
                                <a href="{{ route('admin.registrations') }}?exam_date={{ $slot['exam_date'] }}" 
                                   class="btn btn-sm btn-outline-primary flex-grow-1">
                                    <i class="bi bi-list-ul me-1"></i> รายชื่อ
                                </a>
                                
                                <button type="button" class="btn btn-sm btn-outline-secondary btn-edit-exam-slot"
                                    data-id="{{ $slot['id'] }}"
                                    data-exam_date="{{ $slot['exam_date'] }}"
                                    data-start_time="{{ $slot['start_time'] }}"
                                    data-end_time="{{ $slot['end_time'] }}"
                                    data-max_capacity="{{ $slot['max_capacity'] }}">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                
                                <button type="button" class="btn btn-sm btn-outline-danger btn-delete-exam-slot"
                                    data-slot-id="{{ $slot['id'] }}">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        
        <div class="d-flex justify-content-center mt-4 mb-5">
            {{ $examSlots->appends(request()->except('page_exams'))->fragment('exams-section')->links('pagination::bootstrap-5') }}
        </div>
        
        <!-- Empty State -->
        @if(count($examSlots) === 0)
            <div class="empty-state">
                <div class="empty-state-icon">
                    <i class="bi bi-calendar2-x text-muted" style="font-size: 3rem;"></i>
                </div>
                <h5 class="empty-title">ไม่มีวันสอบ</h5>
                <p class="empty-description">กรุณาเพิ่มวันสอบโดยคลิกปุ่ม "เพิ่มวันสอบ"</p>
                <button class="btn btn-primary mt-2" id="btnAddExamSlotEmpty">
                    <i class="bi bi-plus-lg me-1"></i> เพิ่มวันสอบ
                </button>
            </div>
        @endif
    </div>
</div>

<script>
    // Filter functionality for exams section
    document.addEventListener('DOMContentLoaded', function() {
        const examSection = document.getElementById('exams-section');
        if (!examSection) return;

        // Search filter
        examSection.querySelector('#exam-search').addEventListener('input', function(e) {
            filterExamSlots();
        });
        
        // Month filter
        examSection.querySelector('#exam-month-filter').addEventListener('change', function() {
            filterExamSlots();
        });
        
        // Status filter
        examSection.querySelector('#exam-status-filter').addEventListener('change', function() {
            filterExamSlots();
        });
        
        function filterExamSlots() {
            const searchTerm = examSection.querySelector('#exam-search').value.toLowerCase();
            const monthFilter = examSection.querySelector('#exam-month-filter').value;
            const statusFilter = examSection.querySelector('#exam-status-filter').value;
            
            examSection.querySelectorAll('.exam-slot-item').forEach(item => {
                const date = item.getAttribute('data-date');
                const status = item.getAttribute('data-status');
                const dateText = item.querySelector('.exam-date').textContent.toLowerCase();
                
                // Check search term
                const searchMatch = searchTerm === '' || dateText.includes(searchTerm);
                
                // Check month filter
                const monthMatch = monthFilter === '' || date.startsWith(monthFilter);
                
                // Check status filter
                const statusMatch = statusFilter === '' || status === statusFilter;
                
                // Show/hide based on filters
                item.style.display = (searchMatch && monthMatch && statusMatch) ? 'block' : 'none';
            });
        }
        
        // Initialize filters
        filterExamSlots();
    });
</script>
