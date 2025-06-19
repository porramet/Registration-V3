<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // ข้อมูลจากเซิร์ฟเวอร์
    const appData = {
        registrations: {!! $registrations->map(function($reg) {
            return [
                'id' => $reg->id,
                'faculty_id' => $reg->department->faculty->id ?? null,
                'department_id' => $reg->department->id ?? null,
                'exam_slot_id' => $reg->examSlot->id ?? null,
                'registered_by' => addslashes($reg->registered_by)
            ];
        })->toJson(JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) !!},
        faculties: {!! $faculties->toJson(JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) !!},
        departments: {!! $faculties->flatMap(function($faculty) {
            return $faculty->departments->map(function($dept) use ($faculty) {
                return [
                    'id' => $dept->id,
                    'faculty_id' => $faculty->id,
                    'name' => addslashes($dept->name)
                ];
            });
        })->toJson(JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) !!},
        registeredDepartmentIds: {!! json_encode($registeredDepartmentIds, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) !!},
        examSlots: {!! json_encode($examSlotsArray, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) !!}
    };

    // ตัวแปรสำหรับจัดการสถานะ
    let currentRegistrationId = null;
    let currentExamSlotId = null;
    let formToDelete = null;

    // ฟังก์ชันเริ่มต้นเมื่อหน้าเว็บโหลดเสร็จ
    document.addEventListener('DOMContentLoaded', function() {
        initNavigation();

        // Determine active section based on URL fragment
        const navCards = document.querySelectorAll('.nav-card');
        const sections = {
            'nav-registrations': 'registrations-section',
            'nav-faculties': 'faculties-section',
            'nav-exams': 'exams-section'
        };

        let fragment = window.location.hash.substring(1); // remove '#'
        let activeNavId = null;

        // Find nav card corresponding to fragment
        for (const [navId, sectionId] of Object.entries(sections)) {
            if (sectionId === fragment) {
                activeNavId = navId;
                break;
            }
        }

        // If no valid fragment, default to registrations
        if (!activeNavId) {
            activeNavId = 'nav-registrations';
            fragment = 'registrations-section';
        }

        // Set active nav card and show corresponding section
        navCards.forEach(card => {
            if (card.id === activeNavId) {
                card.classList.add('active');
                card.setAttribute('aria-selected', 'true');
                card.setAttribute('tabindex', '0');
                card.focus();
            } else {
                card.classList.remove('active');
                card.setAttribute('aria-selected', 'false');
                card.setAttribute('tabindex', '-1');
            }
        });

        Object.values(sections).forEach(id => {
            const el = document.getElementById(id);
            if (el) {
                el.classList.toggle('d-block', id === fragment);
                el.classList.toggle('d-none', id !== fragment);
            }
        });

        initModals();
        initEventListeners();
    });

    // ฟังก์ชันจัดการการนำทาง
function initNavigation() {
        const navCards = document.querySelectorAll('.nav-card');
        const sections = {
            'nav-registrations': 'registrations-section',
            'nav-faculties': 'faculties-section',
            'nav-exams': 'exams-section'
        };

        navCards.forEach(card => {
            card.addEventListener('click', function() {
                console.log('Nav card clicked:', this.id);
                // อัปเดตการเลือกเมนู
                navCards.forEach(c => {
                    c.classList.remove('active');
                    c.setAttribute('aria-selected', 'false');
                    c.setAttribute('tabindex', '-1');
                });
                this.classList.add('active');
                this.setAttribute('aria-selected', 'true');
                this.setAttribute('tabindex', '0');
                this.focus();

                // ซ่อนทุก section
                Object.values(sections).forEach(id => {
                    console.log('Hiding section:', id);
                    const el = document.getElementById(id);
                    if (el) {
                        el.classList.add('d-none');
                        el.classList.remove('d-block');
                    }
                });

                // แสดง section ที่เลือก
                const sectionId = sections[this.id];
                if (sectionId) {
                    console.log('Showing section:', sectionId);
                    const el = document.getElementById(sectionId);
                    if (el) {
                        el.classList.remove('d-none');
                        el.classList.add('d-block');
                    }
                }
            });
        });
    }

    // ฟังก์ชันจัดการ Modal ทั้งหมด
    function initModals() {
        // Modal สำหรับแก้ไขการลงทะเบียน
        const editRegistrationModal = new bootstrap.Modal('#editRegistrationModal');
        
        // Modal สำหรับลบการลงทะเบียน
        const deleteRegistrationModal = new bootstrap.Modal('#deleteRegistrationModal');
        
        // Modal สำหรับเพิ่มวันสอบ
        const addExamSlotModal = new bootstrap.Modal('#addExamSlotModal');
        document.getElementById('btnAddExamSlot').addEventListener('click', () => addExamSlotModal.show());
        
        // Modal สำหรับแก้ไขวันสอบ
        const editExamSlotModal = new bootstrap.Modal('#editExamSlotModal');
        
        // Modal สำหรับลบวันสอบ
        const deleteExamSlotModal = new bootstrap.Modal('#deleteExamSlotModal');

        // การจัดการเมื่อ Modal ถูกปิด
        const modals = [
            'editRegistrationModal', 
            'deleteRegistrationModal',
            'addExamSlotModal',
            'editExamSlotModal',
            'deleteExamSlotModal'
        ];

        modals.forEach(modalId => {
            const modal = document.getElementById(modalId);
            if (modal) {
                modal.addEventListener('hidden.bs.modal', () => {
                    // ลบ backdrop ถ้ามี
                    const backdrops = document.getElementsByClassName('modal-backdrop');
                    if (backdrops.length > 0) {
                        backdrops[0].parentNode.removeChild(backdrops[0]);
                    }
                    
                    // ลบคลาส modal-open จาก body
                    document.body.classList.remove('modal-open');
                });
            }
        });
    }

    // ฟังก์ชันตั้งค่า Event Listeners
    function initEventListeners() {
        // การแก้ไขการลงทะเบียน
        window.editRegistration = function(id) {
            const registration = appData.registrations.find(r => r.id == id);
            if (!registration) {
                alert('ไม่พบข้อมูลการลงทะเบียน');
                return;
            }
            
            currentRegistrationId = id;
            const form = document.getElementById('editRegistrationForm');
            form.action = `/admin/registrations/${id}`;
            
            document.getElementById('editFaculty').value = registration.faculty_id || '';
            loadDepartments(registration.faculty_id, 'editDepartment');
            document.getElementById('editDepartment').value = registration.department_id || '';
            document.getElementById('editExamSlot').value = registration.exam_slot_id || '';
            document.getElementById('editRegisteredBy').value = registration.registered_by || '';
            
            const modal = new bootstrap.Modal('#editRegistrationModal');
            modal.show();
        };

        // การลบการลงทะเบียน
        window.deleteRegistration = function(id) {
            currentRegistrationId = id;
            const modal = new bootstrap.Modal('#deleteRegistrationModal');
            modal.show();
        };

        // ยืนยันการลบการลงทะเบียน
        document.getElementById('confirmDeleteBtn').addEventListener('click', function() {
            if (!currentRegistrationId) return;
            
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/admin/registrations/${currentRegistrationId}`;
            
            const csrfToken = document.createElement('input');
            csrfToken.type = 'hidden';
            csrfToken.name = '_token';
            csrfToken.value = '{{ csrf_token() }}';
            form.appendChild(csrfToken);
            
            const methodInput = document.createElement('input');
            methodInput.type = 'hidden';
            methodInput.name = '_method';
            methodInput.value = 'DELETE';
            form.appendChild(methodInput);
            
            document.body.appendChild(form);
            form.submit();
        });

        // การแก้ไขวันสอบ
        document.querySelectorAll('.btn-edit-exam-slot').forEach(button => {
            button.addEventListener('click', function() {
                const slotId = this.dataset.id;
                const slot = appData.examSlots.find(s => s.id == slotId);
                
                if (!slot) return;
                
                currentExamSlotId = slotId;
                const form = document.getElementById('editExamSlotForm');
                form.action = `/admin/exam-slots/${slotId}`;
                
                document.getElementById('edit_exam_date').value = slot.exam_date;
                document.getElementById('edit_start_time').value = slot.start_time;
                document.getElementById('edit_end_time').value = slot.end_time;
                document.getElementById('edit_max_capacity').value = slot.max_capacity;
                
                const modal = new bootstrap.Modal('#editExamSlotModal');
                modal.show();
            });
        });

        // การลบวันสอบ
        document.querySelectorAll('.btn-delete-exam-slot').forEach(button => {
            button.addEventListener('click', function() {
                formToDelete = this.closest('form');
                const modal = new bootstrap.Modal('#deleteExamSlotModal');
                modal.show();
            });
        });

        // ยืนยันการลบวันสอบ
        document.getElementById('confirmDeleteExamSlotBtn').addEventListener('click', function() {
            if (formToDelete) formToDelete.submit();
        });
    }

    // ฟังก์ชันโหลดสาขาวิชาตามคณะ
    window.loadDepartments = function(facultyId, targetSelectId, excludeRegistered = true) {
        const selectElement = document.getElementById(targetSelectId);
        if (!selectElement) return;
        
        selectElement.innerHTML = '<option value="">เลือกสาขา</option>';
        
        if (!facultyId) return;
        
        appData.departments
            .filter(d => d.faculty_id == facultyId)
            .forEach(d => {
                const option = document.createElement('option');
                option.value = d.id;
                option.textContent = d.name;
                
                if (excludeRegistered && appData.registeredDepartmentIds.includes(d.id)) {
                    option.disabled = true;
                    option.textContent += ' (ลงทะเบียนแล้ว)';
                }
                
                selectElement.appendChild(option);
            });
    };
    // Initial setup - select first faculty by default
    document.addEventListener('DOMContentLoaded', function() {
        const firstFaculty = document.querySelector('.faculty-item');
        if (firstFaculty) {
            const facultyId = firstFaculty.getAttribute('data-faculty-id');
            selectFaculty(firstFaculty, facultyId);
        }
    });

    // Faculty selection function
    function selectFaculty(element, facultyId) {
        // Update active state
        document.querySelectorAll('.faculty-item').forEach(item => {
            item.classList.remove('active');
        });
        element.classList.add('active');
        
        // Find the selected faculty data
        const faculty = {!! json_encode($faculties->items()) !!}.find(f => f.id == facultyId);
        
        if (faculty) {
            // Update faculty info
            document.getElementById('selected-faculty-name').textContent = `สาขาวิชา ${faculty.name}`;
            document.getElementById('department-count').textContent = `${faculty.departments.length} สาขา`;
            
            // Calculate total students
            const totalStudents = faculty.departments.reduce((sum, dept) => sum + (dept.student_count || 0), 0);
            document.getElementById('student-total').textContent = `${totalStudents} นักศึกษา`;
            
            // Generate department list HTML
            let html = '';
            faculty.departments.forEach(department => {
                html += `
                <div class="department-item">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-1">${department.name}</h6>
                            <small class="text-muted">รหัสสาขา: ${department.code || '-'}</small>
                        </div>
                        <div class="text-end">
                            <span class="badge bg-primary rounded-pill">${department.student_count || 0} คน</span>
                            <div class="mt-2">
                                <button class="btn btn-sm btn-outline-secondary me-2">
                                    <i class="bi bi-eye"></i> ดูรายชื่อ
                                </button>
                                <button class="btn btn-sm btn-outline-success">
                                    <i class="bi bi-download"></i> Export
                                </button>
                            </div>
                        </div>
                    </div>
                </div>`;
            });
            
            // Show department list
            document.getElementById('no-faculty-selected').style.display = 'none';
            document.getElementById('department-list').style.display = 'block';
            document.getElementById('department-list').innerHTML = html || '<div class="text-center py-5 text-muted">ไม่มีสาขาวิชา</div>';
            
            // Initialize department search
            initDepartmentSearch();
        }
    }
    
    // Faculty search functionality
    document.getElementById('faculty-search').addEventListener('input', function(e) {
        const searchTerm = e.target.value.toLowerCase();
        document.querySelectorAll('.faculty-item').forEach(item => {
            const facultyName = item.querySelector('h6').textContent.toLowerCase();
            item.style.display = facultyName.includes(searchTerm) ? 'block' : 'none';
        });
    });
    
    // Department search functionality
    function initDepartmentSearch() {
        document.getElementById('department-search').addEventListener('input', function(e) {
            const searchTerm = e.target.value.toLowerCase();
            document.querySelectorAll('.department-item').forEach(item => {
                const deptName = item.querySelector('h6').textContent.toLowerCase();
                item.style.display = deptName.includes(searchTerm) ? 'block' : 'none';
            });
        });
    }
</script>
