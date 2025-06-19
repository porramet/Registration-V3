<div class="row mb-4">
    <div class="col-md-3">
        <div class="stat-card dashboard-card">
            <div class="stat-icon">
                <i class="bi bi-people-fill"></i>
            </div>
            <div class="stat-number">{{ $totalRegisteredStudents }} / {{ $totalStudents }}</div>
            <div class="stat-label">นักศึกษาที่ลงทะเบียน / จำนวนทั้งหมดที่มี</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card dashboard-card">
            <div class="stat-icon">
                <i class="bi bi-building"></i>
            </div>
            <div class="stat-number">{{ $totalFaculties }}</div>
            <div class="stat-label">คณะ</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card dashboard-card">
            <div class="stat-icon">
                <i class="bi bi-journals"></i>
            </div>
            <div class="stat-number">{{ $totalDepartments }}</div>
            <div class="stat-label">สาขาวิชา</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card dashboard-card">
            <div class="stat-icon">
                <i class="bi bi-calendar-event"></i>
            </div>
            <div class="stat-number">{{ $totalExamDates }}</div>
            <div class="stat-label">วันสอบ</div>
        </div>
    </div>
</div>
