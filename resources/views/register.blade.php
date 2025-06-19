<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>ลงทะเบียนสอบนักศึกษา</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #4a6bdf;
            --secondary-color: #6c757d;
            --success-color: #28a745;
            --warning-color: #ffc107;
            --danger-color: #dc3545;
            --light-bg: #f8f9fa;
        }

        body {
            font-family: 'Kanit', sans-serif;
            background-color: #f5f7fa;
            min-height: 100vh;
        }

        .registration-container {
            max-width: 1000px;
            margin: 2rem auto;
        }

        .registration-card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.08);
            overflow: hidden;
        }

        .card-header {
            background-color: var(--primary-color);
            color: white;
            padding: 1.25rem 1.5rem;
            border-bottom: none;
        }

        .card-body {
            padding: 2rem;
        }

        .form-section {
            margin-bottom: 2rem;
            padding: 1.5rem;
            background-color: white;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }

        .section-title {
            color: var(--primary-color);
            font-weight: 600;
            margin-bottom: 1.25rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .form-control-custom {
            border-radius: 10px;
            padding: 0.75rem 1rem;
            border: 1px solid #e0e0e0;
            transition: all 0.3s;
        }

        .form-control-custom:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.2rem rgba(74, 107, 223, 0.25);
        }

        .btn-primary-custom {
            background-color: var(--primary-color);
            border: none;
            border-radius: 10px;
            padding: 0.75rem 1.5rem;
            font-weight: 500;
            transition: all 0.3s;
        }

        .btn-primary-custom:hover {
            background-color: #3a5bd9;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }

        .department-card {
            border-radius: 10px;
            border: 1px solid #e0e0e0;
            padding: 1rem;
            margin-bottom: 1rem;
            transition: all 0.3s;
            cursor: pointer;
        }

        .department-card:hover {
            border-color: var(--primary-color);
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
        }

        .department-card.selected {
            border-color: var(--primary-color);
            background-color: rgba(74, 107, 223, 0.05);
        }

        .department-card.disabled {
            opacity: 0.6;
            cursor: not-allowed;
            background-color: var(--light-bg);
        }

        .exam-slot-card {
            border-radius: 10px;
            border: 1px solid #e0e0e0;
            padding: 1rem;
            margin-bottom: 1rem;
            transition: all 0.3s;
            cursor: pointer;
        }

        .exam-slot-card:hover {
            border-color: var(--primary-color);
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
        }

        .exam-slot-card.selected {
            border-color: var(--primary-color);
            background-color: rgba(74, 107, 223, 0.05);
        }

        .exam-slot-card.disabled {
            opacity: 0.6;
            cursor: not-allowed;
            background-color: var(--light-bg);
        }

        .capacity-badge {
            border-radius: 20px;
            padding: 0.25rem 0.75rem;
            font-size: 0.8rem;
            font-weight: 500;
        }

        .available {
            background-color: rgba(40, 167, 69, 0.1);
            color: var(--success-color);
        }

        .warning {
            background-color: rgba(255, 193, 7, 0.1);
            color: var(--warning-color);
        }

        .full {
            background-color: rgba(220, 53, 69, 0.1);
            color: var(--danger-color);
        }

        .summary-card {
            background-color: white;
            border-radius: 10px;
            padding: 1.5rem;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            margin-top: 1.5rem;
        }

        .progress-custom {
            height: 8px;
            border-radius: 4px;
            background-color: #e9ecef;
        }

        .progress-bar-custom {
            background-color: var(--primary-color);
            border-radius: 4px;
        }

        .alert-custom {
            border-radius: 10px;
            padding: 1rem 1.25rem;
        }

        .alert-icon {
            font-size: 1.25rem;
            margin-right: 0.75rem;
        }

        .step-indicator {
            display: flex;
            justify-content: space-between;
            margin-bottom: 2rem;
            position: relative;
        }

        .step-indicator::before {
            content: '';
            position: absolute;
            top: 15px;
            left: 0;
            right: 0;
            height: 2px;
            background-color: #e0e0e0;
            z-index: 0;
        }

        .step {
            display: flex;
            flex-direction: column;
            align-items: center;
            z-index: 1;
            position: relative;
            width: 25%;
        }

        .step-number {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            background-color: #e0e0e0;
            color: #6c757d;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            margin-bottom: 0.5rem;
        }

        .step.active .step-number {
            background-color: var(--primary-color);
            color: white;
        }

        .step-label {
            font-size: 0.85rem;
            color: #6c757d;
            text-align: center;
        }

        .step.active .step-label {
            color: var(--primary-color);
            font-weight: 500;
        }

        .hidden-section {
            display: none;
        }

        .nav-buttons {
            display: flex;
            justify-content: space-between;
            margin-top: 2rem;
        }

        @media (max-width: 768px) {
            .card-body {
                padding: 1.5rem;
            }
            
            .form-section {
                padding: 1rem;
            }
            
            .section-title {
                font-size: 1.1rem;
            }
        }
    </style>
</head>
<body>
<div class="registration-container">
    <div class="registration-card">
        <div class="card-header">
            <h4 class="mb-0"><i class="fas fa-graduation-cap me-2"></i>ลงทะเบียนสอบนักศึกษา</h4>
        </div>
        <div class="card-body">
            <!-- Step Indicator -->
            <div class="step-indicator">
                <div class="step active" id="step1-indicator">
                    <div class="step-number">1</div>
                    <div class="step-label">ข้อมูลพื้นฐาน</div>
                </div>
                <div class="step" id="step2-indicator">
                    <div class="step-number">2</div>
                    <div class="step-label">เลือกวันสอบ</div>
                </div>
                <div class="step" id="step3-indicator">
                    <div class="step-number">3</div>
                    <div class="step-label">เลือกคณะ/สาขา</div>
                </div>
                <div class="step" id="step4-indicator">
                    <div class="step-number">4</div>
                    <div class="step-label">สรุปข้อมูล</div>
                </div>
            </div>

            <!-- Alert Messages -->
            @if(session('success'))
                <div class="alert alert-success alert-custom d-flex align-items-center mb-4">
                    <i class="fas fa-check-circle alert-icon"></i>
                    <div>{{ session('success') }}</div>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-custom d-flex align-items-center mb-4">
                    <i class="fas fa-exclamation-circle alert-icon"></i>
                    <div>{{ session('error') }}</div>
                </div>
            @endif

            <form method="POST" action="{{ route('register.submit') }}" id="registrationForm">
                @csrf

                <!-- Step 1: Basic Information -->
                <div class="form-section" id="step1">
                    <h5 class="section-title"><i class="fas fa-user"></i>ข้อมูลผู้ลงทะเบียน</h5>
                    
                    <div class="alert alert-warning alert-custom d-flex align-items-center mb-4">
                        <i class="fas fa-info-circle alert-icon"></i>
                        <div>
                            <strong>เงื่อนไขการลงทะเบียน:</strong>
                            <ul class="mb-0">
                                <li>สามารถเลือกสาขาได้หลายสาขา แต่รวมจำนวนนักศึกษาไม่เกิน 85 คน</li>
                                <li>สาขาแต่ละสาขาสามารถลงทะเบียนได้เพียง 1 วันสอบเท่านั้น</li>
                                <li>ไม่สามารถเลือกวันสอบที่มีจำนวนผู้ลงทะเบียนเต็มแล้ว</li>
                            </ul>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-medium">ชื่อผู้ลงทะเบียน</label>
                        <input type="text" name="registered_by" class="form-control form-control-custom" placeholder="กรอกชื่อผู้ลงทะเบียน" required>
                    </div>
                    
                    <div class="nav-buttons">
                        <div></div> <!-- Empty div for spacing -->
                        <button type="button" class="btn btn-primary-custom" onclick="nextStep(1)">ต่อไป <i class="fas fa-arrow-right ms-2"></i></button>
                    </div>
                </div>

                <!-- Step 2: Exam Slot Selection -->
                <div class="form-section hidden-section" id="step2">
                    <h5 class="section-title"><i class="fas fa-calendar-alt"></i>เลือกวันและเวลาสอบ</h5>
                    
                    <div id="exam-slot-selection">
                        <label class="form-label fw-medium">เลือกช่วงวันและเวลา</label>
                    <div id="exam-slot-list" class="mb-3">
                        @foreach($examSlots as $slot)
                            @php
                                $statusClass = 'available';
                                $statusText = 'ว่าง';
                                
                                if($slot->remaining_capacity <= 0) {
                                    $statusClass = 'full';
                                    $statusText = 'เต็มแล้ว';
                                } elseif($slot->remaining_capacity < 20) {
                                    $statusClass = 'warning';
                                    $statusText = 'ใกล้เต็ม';
                                }
                            @endphp
                            
                            <div class="exam-slot-card" data-slot-id="{{ $slot->id }}" data-capacity="{{ $slot->remaining_capacity }}" data-total-registered="{{ $slot->total_registered_students }}" style="cursor: pointer;">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
@php
    $formattedDate = \App\Helpers\DateHelper::formatThaiDate($slot->exam_date);
@endphp
<h6 class="mb-1">{{ $formattedDate }}</h6>
                                        <p class="mb-0 text-muted small">เวลา {{ substr($slot->start_time, 0, 5) }} - {{ substr($slot->end_time, 0, 5) }}</p>
                                    </div>
                                    <span class="capacity-badge {{ $statusClass }}">เหลือ {{ $slot->remaining_capacity }} คน</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    </div>
                    
                    <div class="nav-buttons">
                        <button type="button" class="btn btn-outline-secondary" onclick="prevStep(2)"><i class="fas fa-arrow-left me-2"></i> ย้อนกลับ</button>
                        <button type="button" class="btn btn-primary-custom" onclick="nextStep(2)">ต่อไป <i class="fas fa-arrow-right ms-2"></i></button>
                    </div>
                </div>

                <!-- Step 3: Faculty and Department Selection -->
                <div class="form-section hidden-section" id="step3">
                    <h5 class="section-title"><i class="fas fa-building"></i>เลือกคณะและสาขา</h5>
                    
                    <div class="mb-4">
                        <label class="form-label fw-medium">เลือกคณะ</label>
                        <select id="faculty" name="faculty_id" class="form-select form-control-custom" required>
                            <option value="">-- เลือกคณะ --</option>
                            @foreach($faculties as $faculty)
                                <option value="{{ $faculty->id }}">{{ $faculty->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                <div id="department-selection">
                    <label class="form-label fw-medium">เลือกสาขา (เลือกได้หลายรายการ)</label>
                    <div id="department-list" class="mb-3">
                        <div class="text-center text-muted py-4">
                            <i class="fas fa-arrow-up mb-3" style="font-size: 2rem; opacity: 0.5;"></i>
                            <p>กรุณาเลือกคณะก่อน</p>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="total-students-progress" class="form-label fw-medium">จำนวนรวมของนักศึกษา:</label>
                        <div class="progress" style="height: 20px;">
                            <div id="total-students-progress" class="progress-bar" role="progressbar" style="width: 0%;" aria-valuemin="0" aria-valuemax="85" aria-valuenow="0">0 คน</div>
                        </div>
                        <p id="total-registered-students" class="mt-2 fw-medium">จำนวนผู้ลงทะเบียนในวันสอบนี้: 0 คน</p>
                    </div>
                </div>
                    
                    <div class="nav-buttons">
                        <button type="button" class="btn btn-outline-secondary" onclick="prevStep(3)"><i class="fas fa-arrow-left me-2"></i> ย้อนกลับ</button>
                        <button type="button" class="btn btn-primary-custom" onclick="nextStep(3)">ต่อไป <i class="fas fa-arrow-right ms-2"></i></button>
                    </div>
                </div>

                <!-- Step 4: Summary and Submission -->
                <div class="form-section hidden-section" id="step4">
                    <h5 class="section-title"><i class="fas fa-check-circle"></i>สรุปข้อมูลการลงทะเบียน</h5>
                    
                    <div class="summary-card mb-4">
                        <h6 class="fw-medium mb-3"><i class="fas fa-user me-2"></i>ข้อมูลผู้ลงทะเบียน</h6>
                        <p id="summary-registered-by" class="mb-3">-</p>
                        
                        <h6 class="fw-medium mb-3"><i class="fas fa-building me-2"></i>คณะและสาขาที่เลือก</h6>
                        <div id="summary-departments" class="mb-3">
                            <p class="text-muted">ไม่มีสาขาที่เลือก</p>
                        </div>
                        
                        <h6 class="fw-medium mb-3"><i class="fas fa-calendar-day me-2"></i>วันและเวลาสอบ</h6>
                        <p id="summary-exam-slot" class="mb-0">-</p>
                    </div>
                    
                    <div class="alert alert-info alert-custom d-flex align-items-center mb-4">
                        <i class="fas fa-info-circle alert-icon"></i>
                        <div>กรุณาตรวจสอบข้อมูลให้ถูกต้องก่อนกดส่งข้อมูล</div>
                    </div>
                    
                    <div class="nav-buttons">
                        <button type="button" class="btn btn-outline-secondary" onclick="prevStep(4)"><i class="fas fa-arrow-left me-2"></i> ย้อนกลับ</button>
                        <button type="submit" class="btn btn-primary-custom"><i class="fas fa-paper-plane me-2"></i> ส่งข้อมูลลงทะเบียน</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
// Global variables
let selectedDepartments = [];
let selectedExamSlot = null;
let currentStep = 1;

document.querySelector('input[name="registered_by"]').addEventListener('input', function() {
    refreshDepartmentList();
});

function updateRemainingCapacityDisplay() {
    if (!selectedExamSlot) return;
    const slotCard = document.querySelector(`.exam-slot-card[data-slot-id="${selectedExamSlot.id}"]`);
    if (!slotCard) return;
    const remainingCapacity = parseInt(slotCard.dataset.capacity);
    const totalRegisteredDisplay = document.getElementById('total-registered-students');
    if (totalRegisteredDisplay) {
        totalRegisteredDisplay.textContent = `จำนวนที่เหลือในวันสอบนี้: ${remainingCapacity} คน`;
    }
}

const originalSelectExamSlot = selectExamSlot;
selectExamSlot = function() {
    originalSelectExamSlot.apply(this, arguments);
    updateRemainingCapacityDisplay();
};

function enableExamSlotCards() {
    document.querySelectorAll('.exam-slot-card').forEach(card => {
        const capacity = parseInt(card.dataset.capacity);
        if (capacity > 0) {
            card.classList.remove('disabled');
            card.style.cursor = 'pointer';
            card.addEventListener('click', selectExamSlot);
        } else {
            card.classList.add('disabled');
            card.style.cursor = 'not-allowed';
            card.removeEventListener('click', selectExamSlot);
        }
    });
}

// Add event listeners to exam slot cards after DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    enableExamSlotCards();
});

// Function to go to next step
function nextStep(current) {
    // Validate current step before proceeding
    if (current === 1) {
        const registeredByName = document.querySelector('input[name="registered_by"]').value.trim();
        if (!registeredByName) {
            alert('กรุณากรอกชื่อผู้ลงทะเบียน');
            return;
        }
    } else if (current === 2) {
        if (!selectedExamSlot) {
            alert('กรุณาเลือกวันและเวลาสอบ');
            return;
        }
    } else if (current === 3) {
        const facultyId = document.getElementById('faculty').value;
        if (!facultyId) {
            alert('กรุณาเลือกคณะ');
            return;
        }
        
        if (selectedDepartments.length === 0) {
            alert('กรุณาเลือกอย่างน้อย 1 สาขา');
            return;
        }
    }
    
    // Hide current step and show next step
    document.getElementById(`step${current}`).classList.add('hidden-section');
    document.getElementById(`step${current + 1}`).classList.remove('hidden-section');
    
    // Update step indicator
    document.getElementById(`step${current}-indicator`).classList.remove('active');
    document.getElementById(`step${current + 1}-indicator`).classList.add('active');
    
    currentStep = current + 1;

    // Call updateSummary when moving to step 4
    if (current + 1 === 4) {
        updateSummary();
    }
}

// Function to go to previous step
function prevStep(current) {
    document.getElementById(`step${current}`).classList.add('hidden-section');
    document.getElementById(`step${current - 1}`).classList.remove('hidden-section');
    
    // Update step indicator
    document.getElementById(`step${current}-indicator`).classList.remove('active');
    document.getElementById(`step${current - 1}-indicator`).classList.add('active');
    
    currentStep = current - 1;
}

// Function to select exam slot
function selectExamSlot() {
    if (this.classList.contains('disabled')) return;
    
    // Deselect all other slots
    document.querySelectorAll('.exam-slot-card').forEach(card => {
        card.classList.remove('selected');
    });
    
    // Select this slot
    this.classList.add('selected');
    selectedExamSlot = {
        id: this.dataset.slotId,
        date: this.querySelector('h6').textContent,
        time: this.querySelector('p').textContent.replace('เวลา ', ''),
        capacity: this.dataset.capacity
    };
    
    // Refresh department list to update disabled/registered status
    refreshDepartmentList();
}

// Function to update summary information
function updateSummary() {
    // Registered by
    const registeredByName = document.querySelector('input[name="registered_by"]').value;
    document.getElementById('summary-registered-by').textContent = registeredByName;
    
    // Departments
    const facultyName = document.getElementById('faculty').options[document.getElementById('faculty').selectedIndex].text;
    const departmentsList = document.getElementById('summary-departments');
    departmentsList.innerHTML = '';
    
    if (selectedDepartments.length === 0) {
        departmentsList.innerHTML = '<p class="text-muted">ไม่มีสาขาที่เลือก</p>';
    } else {
        const facultyHeader = document.createElement('p');
        facultyHeader.className = 'fw-medium';
        facultyHeader.textContent = facultyName;
        departmentsList.appendChild(facultyHeader);
        
        const ul = document.createElement('ul');
        selectedDepartments.forEach(dept => {
            const li = document.createElement('li');
            li.textContent = `${dept.name} (${dept.count} คน)`;
            ul.appendChild(li);
        });
        departmentsList.appendChild(ul);
        
        const total = selectedDepartments.reduce((sum, dept) => sum + parseInt(dept.count), 0);
        const totalElement = document.createElement('p');
        totalElement.className = 'fw-medium mt-2';
        totalElement.textContent = `รวมทั้งหมด: ${total} คน`;
        departmentsList.appendChild(totalElement);
    }
    
    // Exam slot
    if (selectedExamSlot) {
document.getElementById('summary-exam-slot').textContent = 
    `${selectedExamSlot.date} เวลา ${selectedExamSlot.time}`;
        // Add static exam location text
        const locationElement = document.createElement('p');
        locationElement.className = 'fw-medium mt-2';
        locationElement.textContent = 'สถานที่สอบ ตึก 11 ห้อง 1121-1124';
        document.getElementById('summary-exam-slot').appendChild(locationElement);
    } else {
        document.getElementById('summary-exam-slot').textContent = '-';
    }
}


    function refreshDepartmentList() {
        const facultySelect = document.getElementById('faculty');
        const facultyId = facultySelect ? facultySelect.value : '';
        const registeredByName = document.querySelector('input[name="registered_by"]').value.trim();
        const examSlotId = selectedExamSlot ? selectedExamSlot.id : '';

        if (!facultyId) {
            document.getElementById('department-list').innerHTML = `
                <div class="text-center text-muted py-4">
                    <i class="fas fa-arrow-up mb-3" style="font-size: 2rem; opacity: 0.5;"></i>
                    <p>กรุณาเลือกคณะก่อน</p>
                </div>`;
            return;
        }

        // Fetch departments with registered_by and exam_slot_id query parameters
        const url = new URL(`/departments/${facultyId}`, window.location.origin);
        if (registeredByName) {
            url.searchParams.append('registered_by', registeredByName);
        }
        if (examSlotId) {
            url.searchParams.append('exam_slot_id', examSlotId);
        }

        fetch(url.toString())
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                if (!Array.isArray(data) || data.length === 0) {
                    document.getElementById('department-list').innerHTML = `
                        <div class="text-center text-muted py-4">
                            <i class="fas fa-exclamation-circle mb-3" style="font-size: 2rem; opacity: 0.5;"></i>
                            <p>ไม่มีสาขาสำหรับคณะนี้</p>
                        </div>`;
                    return;
                }

                let html = '';
                data.forEach(dept => {
                    let classes = 'department-card position-relative';
                    let title = '';
                    let badgeHtml = '';
                    if (dept.disabled) {
                        classes += ' disabled';
                        title = 'สาขานี้ถูกลงทะเบียนในวันสอบนี้แล้ว';
                        badgeHtml = `<span class="badge bg-danger ms-2" style="font-size: 0.7rem;">ลงทะเบียนแล้ว</span>`;
                    } else if (dept.registered_in_other_slot) {
                        classes += ' registered-other';
                        title = 'คุณได้ลงทะเบียนสาขานี้ในวันสอบอื่นแล้ว';
                        badgeHtml = `<span class="badge bg-warning text-dark ms-2" style="font-size: 0.7rem;">ลงทะเบียนแล้ว</span>`;
                    }
                    html += `
                        <div class="${classes}" data-dept-id="${dept.id}" data-dept-name="${dept.name}" data-student-count="${dept.student_count}" title="${title}">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-1">${dept.name}</h6>
                                    <p class="mb-0 small text-muted">${dept.description || ''}</p>
                                </div>
                                <div class="d-flex align-items-center">
                                    <span class="badge bg-primary">${dept.student_count} คน</span>
                                    ${badgeHtml}
                                </div>
                            </div>
                        </div>`;
                });

                document.getElementById('department-list').innerHTML = html;

                        // Add event listeners to department cards
                        document.querySelectorAll('.department-card').forEach(card => {
                            card.addEventListener('click', function() {
                                if (this.classList.contains('disabled') || this.classList.contains('registered-other')) return;
                                
                                this.classList.toggle('selected');
                                
                                const deptId = this.dataset.deptId;
                                const deptName = this.dataset.deptName;
                                const studentCount = this.dataset.studentCount;
                                
                                if (this.classList.contains('selected')) {
                                    selectedDepartments.push({
                                        id: deptId,
                                        name: deptName,
                                        count: studentCount
                                    });
                                } else {
                                    selectedDepartments = selectedDepartments.filter(dept => dept.id !== deptId);
                                }
                                updateTotalStudentsCount();
                    });
                });
            })
            .catch(error => {
                console.error('Error fetching departments:', error);
                document.getElementById('department-list').innerHTML = `
                    <div class="alert alert-danger">
                        เกิดข้อผิดพลาดในการโหลดข้อมูลสาขา
                    </div>`;
            });
    }

function updateTotalStudentsCount() {
    const total = selectedDepartments.reduce((sum, dept) => sum + parseInt(dept.count), 0);
    const progressBar = document.getElementById('total-students-progress');
    const maxCapacity = selectedExamSlot ? parseInt(selectedExamSlot.capacity) : 85;
    const percentage = Math.min((total / maxCapacity) * 100, 100);
    progressBar.style.width = percentage + '%';
    progressBar.setAttribute('aria-valuenow', total);
    progressBar.setAttribute('aria-valuemax', maxCapacity);
    progressBar.textContent = total + ' คน';

    // Show alert if total exceeds max capacity
    if (total > maxCapacity) {
        if (!document.getElementById('total-students-alert')) {
            const alertDiv = document.createElement('div');
            alertDiv.id = 'total-students-alert';
            alertDiv.className = 'alert alert-danger mt-2';
            alertDiv.textContent = `จำนวนรวมของนักศึกษาเกิน ${maxCapacity} คน กรุณาลดจำนวนสาขาที่เลือก`;
            const container = document.getElementById('department-selection');
            container.appendChild(alertDiv);
        }
    } else {
        const existingAlert = document.getElementById('total-students-alert');
        if (existingAlert) {
            existingAlert.remove();
        }
    }
}

document.getElementById('faculty').addEventListener('change', function() {
    refreshDepartmentList();
});

// Form submission validation
document.getElementById('registrationForm').addEventListener('submit', function(e) {
    if (selectedDepartments.length === 0) {
        e.preventDefault();
        alert('กรุณาเลือกอย่างน้อย 1 สาขา');
        return false;
    }
    
    if (!selectedExamSlot) {
        e.preventDefault();
        alert('กรุณาเลือกวันและเวลาสอบ');
        return false;
    }
    
    // Calculate total students
    const totalStudents = selectedDepartments.reduce((sum, dept) => sum + parseInt(dept.count), 0);
    
    if (totalStudents > parseInt(selectedExamSlot.capacity)) {
        e.preventDefault();
        alert('จำนวนนักศึกษาที่เลือกเกินความจุของวันสอบที่เลือก กรุณาลดจำนวนสาขาที่เลือก');
        return false;
    }
    
    // Add selected departments to form
    selectedDepartments.forEach(dept => {
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'department_ids[]';
        input.value = dept.id;
        this.appendChild(input);
    });
    
    // Add exam slot to form
    const slotInput = document.createElement('input');
    slotInput.type = 'hidden';
    slotInput.name = 'exam_slot_id';
    slotInput.value = selectedExamSlot.id;
    this.appendChild(slotInput);
});
</script>
</body>
</html>
