<!-- Alert Messages -->
@if(session('success'))
    <div class="alert alert-success alert-custom d-flex align-items-center mb-4">
        <i class="fas fa-check-circle alert-icon"></i>
        <div>{{ session('success') }}</div>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger alert-custom d-flex align-items-center mb-4">
        <i class="fas fa-exclamation-circle alert-icon"></i>
        <div>{{ session('error') }}</div>
    </div>
@endif
