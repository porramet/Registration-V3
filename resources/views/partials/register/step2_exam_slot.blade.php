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
