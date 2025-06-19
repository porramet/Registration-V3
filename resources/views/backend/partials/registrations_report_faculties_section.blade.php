<div class="container-fluid py-4">
    <div class="row">
        <!-- Faculty Selection Panel -->
        <div class="col-md-4">
            <div class="card faculty-selection-card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        <i class="bi bi-building"></i> 
                        เลือกคณะ
                        <span class="badge bg-white text-primary ms-2">{{ count($faculties) }} คณะ</span>
                    </h5>
                </div>
                <div class="card-body p-0">
                    <div class="search-box p-3 border-bottom">
                        <div class="input-group">
                            <span class="input-group-text bg-light"><i class="bi bi-search"></i></span>
                            <input type="text" class="form-control" placeholder="ค้นหาคณะ..." id="faculty-search">
                        </div>
                    </div>
                    <div class="faculty-list" style="max-height: 600px; overflow-y: auto;">
                        @foreach ($faculties as $index => $faculty)
                            <div class="faculty-item p-3 border-bottom cursor-pointer 
                                {{ $index === 0 ? 'active' : '' }}" 
                                data-faculty-id="{{ $faculty['id'] }}"
                                onclick="selectFaculty(this, {{ $faculty['id'] }})">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="mb-0">{{ $faculty['name'] }}</h6>
                                        <small class="text-muted">{{ count($faculty['departments']) }} สาขาวิชา</small>
                                    </div>
                                    <i class="bi bi-chevron-right"></i>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- Department Display Panel -->
        <div class="col-md-8">
            <div class="card department-display-card shadow-sm">
                <div class="card-header bg-info text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="bi bi-journals"></i> 
                        <span id="selected-faculty-name">สาขาวิชา</span>
                    </h5>
                    <div>
                        <span class="badge bg-white text-info me-2" id="department-count">0 สาขา</span>
                        <span class="badge bg-white text-info" id="student-total">0 นักศึกษา</span>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="search-box p-3 border-bottom">
                        <div class="input-group">
                            <span class="input-group-text bg-light"><i class="bi bi-search"></i></span>
                            <input type="text" class="form-control" placeholder="ค้นหาสาขาวิชา..." id="department-search">
                        </div>
                    </div>
                    <div class="department-list-container" style="max-height: 600px; overflow-y: auto;">
                        <div class="text-center py-5" id="no-faculty-selected">
                            <i class="bi bi-building text-muted" style="font-size: 3rem;"></i>
                            <h5 class="mt-3 text-muted">กรุณาเลือกคณะ</h5>
                            <p class="text-muted">เลือกคณะจากเมนูด้านซ้ายเพื่อแสดงรายการสาขาวิชา</p>
                        </div>
                        <div id="department-list" style="display: none;">
                            <!-- Departments will be loaded here -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



