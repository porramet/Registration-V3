<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ระบบลงทะเบียนสอบ</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        :root {
            --primary-color: #3b82f6;
            --primary-light: #93c5fd;
            --secondary-color: #64748b;
            --success-color: #10b981;
            --warning-color: #f59e0b;
            --danger-color: #ef4444;
            --light-bg: #f8fafc;
            --card-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            --border-radius: 12px;
        }

        body {
            background-color: var(--light-bg);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .main-header {
            background: linear-gradient(135deg, var(--primary-color), #2563eb);
            color: white;
            padding: 1.5rem 0;
            margin-bottom: 2rem;
            box-shadow: var(--card-shadow);
        }

        .header-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .dashboard-card {
            border: none;
            border-radius: var(--border-radius);
            box-shadow: var(--card-shadow);
            transition: all 0.3s ease;
            height: 100%;
            overflow: hidden;
        }

        .dashboard-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.15);
        }

        .card-header {
            background: linear-gradient(135deg, var(--primary-color), #2563eb);
            color: white;
            padding: 1.25rem;
            border-bottom: none;
        }

        .card-body {
            padding: 1.5rem;
        }

        .nav-card {
            cursor: pointer;
            transition: all 0.3s ease;
            border: 2px solid transparent;
        }

        .nav-card:hover {
            border-color: var(--primary-light);
            transform: scale(1.03);
        }

        .nav-card.active {
            border-color: var(--primary-color);
            background-color: rgba(59, 130, 246, 0.05);
        }

        .nav-icon {
            font-size: 2rem;
            margin-bottom: 1rem;
            color: var(--primary-color);
        }

        .stat-card {
            text-align: center;
            padding: 1.5rem;
        }

        .stat-icon {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem;
            font-size: 1.75rem;
            color: white;
            background: linear-gradient(135deg, var(--primary-color), #2563eb);
        }

        .stat-number {
            font-size: 2rem;
            font-weight: 700;
            color: var(--primary-color);
            margin-bottom: 0.5rem;
        }

        .stat-label {
            color: var(--secondary-color);
            font-weight: 500;
            font-size: 0.9rem;
        }

        .faculty-card {
            position: relative;
            overflow: hidden;
        }

        .faculty-card::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 5px;
            height: 100%;
            background: linear-gradient(to bottom, var(--primary-color), #2563eb);
        }

        .department-badge {
            background-color: #e2e8f0;
            color: #1e293b;
            padding: 0.35rem 0.75rem;
            border-radius: 20px;
            margin-right: 0.5rem;
            margin-bottom: 0.5rem;
            display: inline-block;
            font-size: 0.85rem;
            transition: all 0.2s ease;
        }

        .department-badge:hover {
            background-color: var(--primary-light);
            transform: translateY(-2px);
        }

        .exam-card {
            position: relative;
            overflow: hidden;
        }

        .exam-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(to right, var(--primary-color), #2563eb);
        }

        .exam-date {
            font-weight: 600;
            color: var(--primary-color);
            margin-bottom: 0.5rem;
        }

        .exam-time {
            color: var(--secondary-color);
            font-size: 0.9rem;
        }

        .registration-card {
            position: relative;
            border-left: 4px solid var(--primary-color);
        }

        .user-avatar {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            font-weight: 600;
            color: white;
            background: linear-gradient(135deg, var(--primary-color), #2563eb);
        }

        .search-section {
            background: white;
            border-radius: var(--border-radius);
            padding: 1.5rem;
            margin-bottom: 2rem;
            box-shadow: var(--card-shadow);
        }

        .action-btn {
            width: 36px;
            height: 36px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            border: none;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .action-btn:hover {
            transform: scale(1.1);
        }

        @media (max-width: 992px) {
            .nav-card {
                margin-bottom: 1rem;
            }
        }

        @media (max-width: 768px) {
            .header-content {
                flex-direction: column;
                text-align: center;
                gap: 1rem;
            }
            
            .stat-card {
                margin-bottom: 1rem;
            }
        }
    .exam-time-card {
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
        border: 1px solid #e9ecef;
    }
    
    .exam-time-card .card-header {
        padding: 12px 20px;
        border-bottom: none;
    }
    
    .registration-card {
        border-radius: 8px;
        border: 1px solid #e9ecef;
        transition: all 0.2s ease;
        height: 100%;
    }
    
    .registration-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        border-color: var(--primary-color);
    }
    
    .user-avatar {
        width: 48px;
        height: 48px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
        font-weight: 600;
        color: white;
        background: linear-gradient(135deg, var(--primary-color), #2563eb);
    }
    
    .empty-state {
        background-color: #f8f9fa;
        border-radius: 10px;
        padding: 40px 20px;
    }
    
    .empty-state-icon {
        margin-bottom: 20px;
    }
    .summary-container {
        background: #f8fafc;
        padding: 1.5rem;
        border-radius: 12px;
    }

    .custom-summary-card {
        background-color: #ffffff;
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
    }

    .card-header-custom {
        background: linear-gradient(to right, #3b82f6, #2563eb);
        padding: 1rem 1.25rem;
        border-bottom: 1px solid #e5e7eb;
    }

    .card-header-custom .exam-date {
        font-size: 1.1rem;
        color: white;
    }

    .card-header-custom .exam-time {
        font-size: 0.9rem;
        margin-top: 0.25rem;
        color: white;
    }

    .total-label {
        font-size: 0.95rem;
        color: #1a202c;
    }

.bg-soft-primary {
    background-color: rgba(13, 110, 253, 0.1) !important;
    color: #0d6efd !important;
}

/* Registration Grid */
.registration-grid {
    padding: 24px;
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 20px;
}

/* Registration Card */
.registration-card {
    background: #fff;
    border: 1px solid #e9ecef;
    border-radius: 8px;
    overflow: hidden;
    transition: all 0.3s ease;
    position: relative;
}

.registration-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    border-color: #0d6efd;
}

/* Card Header */
.card-header-section {
    background: #f8f9fa;
    padding: 12px 16px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    border-bottom: 1px solid #e9ecef;
}

.registration-number {
    background: #0d6efd;
    color: white;
    width: 28px;
    height: 28px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
    font-size: 0.9rem;
}

.action-buttons {
    display: flex;
    gap: 8px;
}

.action-btn {
    width: 32px;
    height: 32px;
    border: none;
    border-radius: 6px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.2s ease;
    font-size: 0.9rem;
}

.btn-edit {
    background: #e3f2fd;
    color: #1976d2;
}

.btn-edit:hover {
    background: #bbdefb;
    transform: scale(1.05);
}

.btn-delete {
    background: #ffebee;
    color: #d32f2f;
}

.btn-delete:hover {
    background: #ffcdd2;
    transform: scale(1.05);
}

/* Card Body */
.card-body-section {
    padding: 16px;
}

.registrant-info {
    display: flex;
    align-items: center;
    margin-bottom: 16px;
}

.user-avatar {
    width: 48px;
    height: 48px;
    border-radius: 50%;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
    font-size: 1.2rem;
    margin-right: 12px;
    flex-shrink: 0;
}

.user-details {
    flex: 1;
    min-width: 0;
}

.user-name {
    font-weight: 600;
    color: #495057;
    margin: 0 0 4px 0;
    font-size: 1.1rem;
}

.faculty-name {
    color: #6c757d;
    font-size: 0.9rem;
    display: block;
}

.department-info {
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.info-item {
    display: flex;
    align-items: center;
    font-size: 0.9rem;
}

.info-label {
    font-weight: 500;
    color: #6c757d;
    margin-right: 8px;
}

.info-value {
    color: #495057;
    font-weight: 500;
}

.info-value.highlight {
    color: #0d6efd;
    font-weight: 600;
}

.student-count {
    background: #f8f9fa;
    padding: 8px 12px;
    border-radius: 6px;
    margin-top: 4px;
}

/* Empty State */
.empty-state {
    text-align: center;
    padding: 60px 20px;
    color: #6c757d;
}

.empty-icon {
    font-size: 4rem;
    margin-bottom: 20px;
    opacity: 0.5;
}

.empty-title {
    color: #495057;
    margin-bottom: 8px;
    font-weight: 600;
}

.empty-description {
    margin: 0;
    font-size: 0.95rem;
}

/* Responsive Design */
@media (max-width: 768px) {
    .registration-grid {
        grid-template-columns: 1fr;
        padding: 16px;
    }
    
    .exam-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 12px;
    }
    
    .exam-date-info,
    .exam-time-info {
        width: 100%;
        justify-content: flex-start;
    }
    
    .exam-stats {
        align-self: flex-end;
    }
}

@media (max-width: 480px) {
    .registration-grid {
        padding: 12px;
    }
    
    .registration-card {
        min-width: 0;
    }
    
    .registrant-info {
        flex-direction: column;
        align-items: flex-start;
        text-align: center;
    }
    
    .user-avatar {
        margin-right: 0;
        margin-bottom: 8px;
    }
}
    .summary-card {
        background-color: #ffffff;
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
    }

    .summary-header {
        padding: 1rem 1.25rem;
        border-bottom: 1px solid #e5e7eb;
    }

    .summary-info {
        font-size: 1rem;
    }

    .summary-date {
        font-weight: 600;
        color: #495057;
    }

    .summary-time {
        font-size: 0.9rem;
        color: #6c757d;
    }

    .summary-badge {
        font-size: 0.95rem;
    }

    .summary-table {
        margin-bottom: 0;
    }
    /* Faculty Selection Card */
    .faculty-selection-card {
        border-radius: 12px;
        height: 100%;
    }
    
    .faculty-item {
        transition: all 0.2s ease;
    }
    
    .faculty-item:hover {
        background-color: #f8f9fa;
    }
    
    .faculty-item.active {
        background-color: #e9f5ff;
        border-left: 4px solid #0d6efd !important;
    }
    
    /* Department Display Card */
    .department-display-card {
        border-radius: 12px;
        height: 100%;
    }
    
    .department-item {
        padding: 1rem;
        border-bottom: 1px solid #eee;
        transition: all 0.2s ease;
    }
    
    .department-item:hover {
        background-color: #f8f9fa;
    }
    
    .cursor-pointer {
        cursor: pointer;
    }
    
    /* Responsive Design */
    @media (max-width: 768px) {
        .row {
            flex-direction: column;
        }
        
        .col-md-4, .col-md-8 {
            width: 100%;
        }
        
        .faculty-selection-card, .department-display-card {
            margin-bottom: 1rem;
        }
    }
</style>
</head>
<body>
    @include('backend.partials.registrations_report_header')
    <div class="container">
        @include('backend.partials.registrations_report_alerts')
        @include('backend.partials.registrations_report_dashboard_stats')
        @include('backend.partials.registrations_report_navigation_cards')

        <div id="registrations-section">
            @include('backend.partials.registrations_report_registrations_section')
        </div>

        <div id="faculties-section">
            @include('backend.partials.registrations_report_faculties_section')
        </div>

        <div id="exams-section">
            @include('backend.partials.registrations_report_exams_section')
        </div>

        @include('backend.partials.registrations_report_modals')
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @include('backend.partials.registrations_report_scripts')
</body>
</html>