<div class="search-section dashboard-card">
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
</div>
