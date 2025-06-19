<!DOCTYPE html>
<html lang="th">
<head>
    @include('partials.register.head')
</head>
<body>
<div class="registration-container">
    <div class="registration-card">
        <div class="card-header">
            <h4 class="mb-0"><i class="fas fa-graduation-cap me-2"></i>ลงทะเบียนสอบนักศึกษา</h4>
        </div>
        <div class="card-body">
            @include('partials.register.step_indicator')

            @include('partials.register.alert_messages')

            <form method="POST" action="{{ route('register.submit') }}" id="registrationForm">
                @csrf

                @include('partials.register.step1_basic_info')

                @include('partials.register.step2_exam_slot')

                @include('partials.register.step3_faculty_department')

                @include('partials.register.step4_summary')
            </form>
        </div>
    </div>
</div>

@include('partials.register.scripts')
</body>
</html>
