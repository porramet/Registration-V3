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
