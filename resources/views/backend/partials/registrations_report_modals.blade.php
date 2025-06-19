<div class="modal fade" id="addExamSlotModal" tabindex="-1" aria-labelledby="addExamSlotModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form method="POST" action="{{ route('admin.exam-slots.store') }}">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addExamSlotModalLabel">เพิ่มวันสอบ</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="exam_date" class="form-label">วันสอบ</label>
                        <input type="date" class="form-control" id="exam_date" name="exam_date" required>
                    </div>
                    <div class="mb-3">
                        <label for="start_time" class="form-label">เวลาเริ่มต้น</label>
                        <input type="time" class="form-control" id="start_time" name="start_time" required>
                    </div>
                    <div class="mb-3">
                        <label for="end_time" class="form-label">เวลาสิ้นสุด</label>
                        <input type="time" class="form-control" id="end_time" name="end_time" required>
                    </div>
                    <div class="mb-3">
                        <label for="max_capacity" class="form-label">ความจุสูงสุด</label>
                        <input type="number" class="form-control" id="max_capacity" name="max_capacity" min="1" value="85" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">บันทึก</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ยกเลิก</button>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="modal fade" id="deleteExamSlotModal" tabindex="-1" aria-labelledby="deleteExamSlotModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="deleteExamSlotModalLabel">ยืนยันการลบ</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                คุณแน่ใจหรือว่าต้องการลบวันสอบนี้?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ยกเลิก</button>
                <button type="button" class="btn btn-danger" id="confirmDeleteExamSlotBtn">ลบ</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="editExamSlotModal" tabindex="-1" aria-labelledby="editExamSlotModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form id="editExamSlotForm" method="POST" action="">
            @csrf
            @method('PUT')
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editExamSlotModalLabel">แก้ไขวันสอบ</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="edit_exam_date" class="form-label">วันสอบ</label>
                        <input type="date" class="form-control" id="edit_exam_date" name="exam_date">
                    </div>
                    <div class="mb-3">
                        <label for="edit_start_time" class="form-label">เวลาเริ่มต้น</label>
                        <input type="time" class="form-control" id="edit_start_time" name="start_time">
                    </div>
                    <div class="mb-3">
                        <label for="edit_end_time" class="form-label">เวลาสิ้นสุด</label>
                        <input type="time" class="form-control" id="edit_end_time" name="end_time">
                    </div>
                    <div class="mb-3">
                        <label for="edit_max_capacity" class="form-label">ความจุสูงสุด</label>
                        <input type="number" class="form-control" id="edit_max_capacity" name="max_capacity" min="1" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">บันทึก</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ยกเลิก</button>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="modal fade" id="editRegistrationModal" tabindex="-1" aria-labelledby="editRegistrationModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form id="editRegistrationForm" method="POST" action="">
            @csrf
            @method('PUT')
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editRegistrationModalLabel">แก้ไขการลงทะเบียน</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="editFaculty" class="form-label">คณะ</label>
                        <select id="editFaculty" name="faculty_id" class="form-select" onchange="loadDepartments(this.value, 'editDepartment')" required>
                            <option value="">เลือกคณะ</option>
                            @foreach ($faculties as $faculty)
                                <option value="{{ $faculty->id }}">{{ $faculty->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="editDepartment" class="form-label">สาขา</label>
                        <select id="editDepartment" name="department_id" class="form-select" required>
                            <option value="">เลือกสาขา</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="editExamSlot" class="form-label">วันสอบ</label>
                        <select id="editExamSlot" name="exam_slot_id" class="form-select" required>
                            @foreach ($examSlots as $slot)
                                <option value="{{ $slot->id }}">{{ $slot->exam_date }} {{ $slot->start_time }} - {{ $slot->end_time }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="editRegisteredBy" class="form-label">ชื่อผู้ลงทะเบียน</label>
                        <input type="text" id="editRegisteredBy" name="registered_by" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">บันทึก</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ยกเลิก</button>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="modal fade" id="deleteRegistrationModal" tabindex="-1" aria-labelledby="deleteRegistrationModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="deleteRegistrationModalLabel">ยืนยันการลบ</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                คุณแน่ใจหรือว่าต้องการลบการลงทะเบียนนี้?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ยกเลิก</button>
                <button type="button" class="btn btn-danger" id="confirmDeleteBtn">ลบ</button>
            </div>
        </div>
    </div>
</div>
