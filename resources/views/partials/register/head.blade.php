<meta charset="UTF-8">
<title>ลงทะเบียนสอบนักศึกษา</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
<style>
    :root {
        --primary-color: #4a6bdf;
        --secondary-color: #6c757d;
        --success-color: #28a745;
        --warning-color: #ffc107;
        --danger-color: #dc3545;
        --light-bg: #f8f9fa;
    }

    body {
        font-family: 'Kanit', sans-serif;
        background-color: #f5f7fa;
        min-height: 100vh;
    }

    .registration-container {
        max-width: 1000px;
        margin: 2rem auto;
    }

    .registration-card {
        border: none;
        border-radius: 15px;
        box-shadow: 0 5px 20px rgba(0,0,0,0.08);
        overflow: hidden;
    }

    .card-header {
        background-color: var(--primary-color);
        color: white;
        padding: 1.25rem 1.5rem;
        border-bottom: none;
    }

    .card-body {
        padding: 2rem;
    }

    .form-section {
        margin-bottom: 2rem;
        padding: 1.5rem;
        background-color: white;
        border-radius: 12px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    }

    .section-title {
        color: var(--primary-color);
        font-weight: 600;
        margin-bottom: 1.25rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .form-control-custom {
        border-radius: 10px;
        padding: 0.75rem 1rem;
        border: 1px solid #e0e0e0;
        transition: all 0.3s;
    }

    .form-control-custom:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 0.2rem rgba(74, 107, 223, 0.25);
    }

    .btn-primary-custom {
        background-color: var(--primary-color);
        border: none;
        border-radius: 10px;
        padding: 0.75rem 1.5rem;
        font-weight: 500;
        transition: all 0.3s;
    }

    .btn-primary-custom:hover {
        background-color: #3a5bd9;
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }

    .department-card {
        border-radius: 10px;
        border: 1px solid #e0e0e0;
        padding: 1rem;
        margin-bottom: 1rem;
        transition: all 0.3s;
        cursor: pointer;
    }

    .department-card:hover {
        border-color: var(--primary-color);
        box-shadow: 0 4px 12px rgba(0,0,0,0.08);
    }

    .department-card.selected {
        border-color: var(--primary-color);
        background-color: rgba(74, 107, 223, 0.05);
    }

    .department-card.disabled {
        opacity: 0.6;
        cursor: not-allowed;
        background-color: var(--light-bg);
    }

    .exam-slot-card {
        border-radius: 10px;
        border: 1px solid #e0e0e0;
        padding: 1rem;
        margin-bottom: 1rem;
        transition: all 0.3s;
        cursor: pointer;
    }

    .exam-slot-card:hover {
        border-color: var(--primary-color);
        box-shadow: 0 4px 12px rgba(0,0,0,0.08);
    }

    .exam-slot-card.selected {
        border-color: var(--primary-color);
        background-color: rgba(74, 107, 223, 0.05);
    }

    .exam-slot-card.disabled {
        opacity: 0.6;
        cursor: not-allowed;
        background-color: var(--light-bg);
    }

    .capacity-badge {
        border-radius: 20px;
        padding: 0.25rem 0.75rem;
        font-size: 0.8rem;
        font-weight: 500;
    }

    .available {
        background-color: rgba(40, 167, 69, 0.1);
        color: var(--success-color);
    }

    .warning {
        background-color: rgba(255, 193, 7, 0.1);
        color: var(--warning-color);
    }

    .full {
        background-color: rgba(220, 53, 69, 0.1);
        color: var(--danger-color);
    }

    .summary-card {
        background-color: white;
        border-radius: 10px;
        padding: 1.5rem;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        margin-top: 1.5rem;
    }

    .progress-custom {
        height: 8px;
        border-radius: 4px;
        background-color: #e9ecef;
    }

    .progress-bar-custom {
        background-color: var(--primary-color);
        border-radius: 4px;
    }

    .alert-custom {
        border-radius: 10px;
        padding: 1rem 1.25rem;
    }

    .alert-icon {
        font-size: 1.25rem;
        margin-right: 0.75rem;
    }

    .step-indicator {
        display: flex;
        justify-content: space-between;
        margin-bottom: 2rem;
        position: relative;
    }

    .step-indicator::before {
        content: '';
        position: absolute;
        top: 15px;
        left: 0;
        right: 0;
        height: 2px;
        background-color: #e0e0e0;
        z-index: 0;
    }

    .step {
        display: flex;
        flex-direction: column;
        align-items: center;
        z-index: 1;
        position: relative;
        width: 25%;
    }

    .step-number {
        width: 30px;
        height: 30px;
        border-radius: 50%;
        background-color: #e0e0e0;
        color: #6c757d;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        margin-bottom: 0.5rem;
    }

    .step.active .step-number {
        background-color: var(--primary-color);
        color: white;
    }

    .step-label {
        font-size: 0.85rem;
        color: #6c757d;
        text-align: center;
    }

    .step.active .step-label {
        color: var(--primary-color);
        font-weight: 500;
    }

    .hidden-section {
        display: none;
    }

    .nav-buttons {
        display: flex;
        justify-content: space-between;
        margin-top: 2rem;
    }

    @media (max-width: 768px) {
        .card-body {
            padding: 1.5rem;
        }
        
        .form-section {
            padding: 1rem;
        }
        
        .section-title {
            font-size: 1.1rem;
        }
    }
</style>
