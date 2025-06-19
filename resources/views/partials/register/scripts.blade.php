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
        locationElement.textContent = 'สถานที่สอบ ตึก 11 ห้อง 1124-1125';
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
