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
