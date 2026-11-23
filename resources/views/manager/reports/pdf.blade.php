<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Official Report #REP-{{ $report->id }}</title>
    <style>
        @page {
            margin: 30px 35px 50px 35px;
        }

        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            color: #1e293b;
            font-size: 11px;
            line-height: 1.4;
            background-color: #ffffff;
            margin: 0;
            padding: 0;
        }

        /* Header Syarikat / Letterhead */
        .company-header {
            width: 100%;
            border-bottom: 2.5px solid #6d28d9;
            padding-bottom: 12px;
            margin-bottom: 20px;
        }

        .company-title {
            font-size: 20px;
            font-weight: 800;
            color: #2e1065;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin: 0;
        }

        .company-subtitle {
            font-size: 9px;
            color: #64748b;
            margin-top: 2px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .report-badge {
            background-color: #f3e8ff;
            color: #6d28d9;
            font-size: 9px;
            font-weight: bold;
            padding: 4px 10px;
            border-radius: 12px;
            text-transform: uppercase;
            display: inline-block;
        }

        /* Ringkasan Maklumat Laporan */
        .info-table {
            width: 100%;
            margin-bottom: 20px;
            border-collapse: collapse;
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
        }

        .info-table td {
            padding: 10px 14px;
            width: 25%;
            vertical-align: top;
            border-right: 1px solid #e2e8f0;
        }

        .info-table td:last-child {
            border-right: none;
        }

        .info-label {
            font-size: 8px;
            font-weight: bold;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            display: block;
            margin-bottom: 3px;
        }

        .info-value {
            font-size: 11px;
            font-weight: bold;
            color: #0f172a;
        }

        /* Section Headings */
        .section-title {
            font-size: 11px;
            font-weight: bold;
            color: #2e1065;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-top: 22px;
            margin-bottom: 10px;
            padding-bottom: 4px;
            border-bottom: 1px solid #cbd5e1;
        }

        /* KPI Cards Grid */
        .kpi-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 12px 0;
            margin-left: -12px;
            margin-right: -12px;
            margin-bottom: 20px;
        }

        .kpi-card {
            background-color: #faf5ff;
            border: 1px solid #e9d5ff;
            border-left: 4px solid #6d28d9;
            border-radius: 6px;
            padding: 12px 16px;
            width: 50%;
        }

        .kpi-card-success {
            background-color: #f0fdf4;
            border: 1px solid #bbf7d0;
            border-left: 4px solid #16a34a;
            border-radius: 6px;
            padding: 12px 16px;
            width: 50%;
        }

        .kpi-title {
            font-size: 9px;
            font-weight: bold;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .kpi-value {
            font-size: 20px;
            font-weight: 800;
            color: #1e1b4b;
            margin-top: 4px;
        }

        /* Main Data Table */
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            font-size: 10px;
        }

        .data-table th {
            background-color: #2e1065;
            color: #ffffff;
            font-size: 9px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            padding: 8px 12px;
            text-align: left;
        }

        .data-table td {
            padding: 9px 12px;
            border-bottom: 1px solid #e2e8f0;
            color: #334155;
        }

        .data-table tr:nth-child(even) {
            background-color: #f8fafc;
        }

        /* Footer & Signature Section */
        .footer-signatures {
            width: 100%;
            margin-top: 40px;
            border-collapse: collapse;
        }

        .footer-signatures td {
            width: 50%;
            vertical-align: bottom;
        }

        .sig-box {
            width: 180px;
            border-top: 1px solid #94a3b8;
            padding-top: 6px;
            text-align: center;
            font-size: 9px;
            color: #475569;
            font-weight: bold;
        }

        .confidential-notice {
            position: fixed;
            bottom: -20px;
            left: 0;
            right: 0;
            text-align: center;
            font-size: 8px;
            color: #94a3b8;
            border-top: 1px solid #f1f5f9;
            padding-top: 6px;
        }
    </style>
</head>
<body>

    <!-- Letterhead Syarikat -->
    <table class="company-header">
        <tr>
            <td>
                <h1 class="company-title">Vision Concept Eyewear</h1>
                <div class="company-subtitle">Management Operations & Executive Analytics Report</div>
            </td>
            <td style="text-align: right; vertical-align: middle;">
                <span class="report-badge">Official Record</span>
            </td>
        </tr>
    </table>

    <!-- Grid Maklumat Ringkas Laporan -->
    <table class="info-table">
        <tr>
            <td>
                <span class="info-label">Report ID</span>
                <span class="info-value">#REP-{{ sprintf('%05d', $report->id) }}</span>
            </td>
            <td>
                <span class="info-label">Report Category</span>
                <span class="info-value">{{ $report->report_type }}</span>
            </td>
            <td>
                <span class="info-label">Date Range</span>
                <span class="info-value">{{ \Carbon\Carbon::parse($report->start_date)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($report->end_date)->format('d/m/Y') }}</span>
            </td>
            <td>
                <span class="info-label">Generated On</span>
                <span class="info-value">{{ $report->created_at->format('d M Y, h:i A') }}</span>
            </td>
        </tr>
    </table>

    <!-- Tajuk Bahagian KPI -->
    <div class="section-title">Key Performance Indicators (KPI Summary)</div>

    <!-- Kad Statistik Berkotak (KPI Cards) -->
    <table class="kpi-table">
        <tr>
            @if($report->report_type === 'Appointment Report')
                <td class="kpi-card">
                    <div class="kpi-title">Total Appointments Scheduled</div>
                    <div class="kpi-value">{{ number_format($report->data_summary['total_appointments'] ?? 0) }} Persons</div>
                </td>
                <td class="kpi-card-success">
                    <div class="kpi-title">Total Attended / Completed</div>
                    <div class="kpi-value">{{ number_format($report->data_summary['total_attended'] ?? 0) }} Persons</div>
                </td>
            @else
                <td class="kpi-card">
                    <div class="kpi-title">Total Gross Revenue</div>
                    <div class="kpi-value">RM {{ number_format($report->data_summary['total_sales'] ?? 0, 2) }}</div>
                </td>
                <td class="kpi-card-success">
                    <div class="kpi-title">Total Orders Processed</div>
                    <div class="kpi-value">{{ number_format($report->data_summary['total_orders'] ?? 0) }} Orders</div>
                </td>
            @endif
        </tr>
    </table>

    <!-- Breakdown Table -->
    <div class="section-title">Report Metrics Breakdown</div>
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 10%;">No</th>
                <th style="width: 45%;">Metric Indicator</th>
                <th style="width: 25%;">Recorded Value</th>
                <th style="width: 20%;">Operational Status</th>
            </tr>
        </thead>
        <tbody>
            @if($report->report_type === 'Appointment Report')
                <tr>
                    <td style="font-weight: bold; text-align: center;">01</td>
                    <td>Total Customer Appointments</td>
                    <td style="font-weight: bold;">{{ $report->data_summary['total_appointments'] ?? 0 }} Records</td>
                    <td><span style="color: #16a34a; font-weight: bold;">VERIFIED</span></td>
                </tr>
                <tr>
                    <td style="font-weight: bold; text-align: center;">02</td>
                    <td>Attended & Attain Ratio</td>
                    <td style="font-weight: bold;">
                        @php
                            $total = $report->data_summary['total_appointments'] ?? 0;
                            $attended = $report->data_summary['total_attended'] ?? 0;
                            $percentage = $total > 0 ? round(($attended / $total) * 100, 1) : 0;
                        @endphp
                        {{ $attended }} Persons ({{ $percentage }}%)
                    </td>
                    <td><span style="color: #16a34a; font-weight: bold;">COMPLETED</span></td>
                </tr>
            @else
                <tr>
                    <td style="font-weight: bold; text-align: center;">01</td>
                    <td>Total Generated Revenue</td>
                    <td style="font-weight: bold; color: #2e1065;">RM {{ number_format($report->data_summary['total_sales'] ?? 0, 2) }}</td>
                    <td><span style="color: #16a34a; font-weight: bold;">AUDITED</span></td>
                </tr>
                <tr>
                    <td style="font-weight: bold; text-align: center;">02</td>
                    <td>Total Successful Customer Orders</td>
                    <td style="font-weight: bold;">{{ $report->data_summary['total_orders'] ?? 0 }} Orders</td>
                    <td><span style="color: #16a34a; font-weight: bold;">CLOSED</span></td>
                </tr>
                <tr>
                    <td style="font-weight: bold; text-align: center;">03</td>
                    <td>Average Order Value (AOV)</td>
                    <td style="font-weight: bold;">
                        @php
                            $sales = $report->data_summary['total_sales'] ?? 0;
                            $orders = $report->data_summary['total_orders'] ?? 0;
                            $aov = $orders > 0 ? $sales / $orders : 0;
                        @endphp
                        RM {{ number_format($aov, 2) }}
                    </td>
                    <td><span style="color: #6d28d9; font-weight: bold;">CALCULATED</span></td>
                </tr>
            @endif
        </tbody>
    </table>

    <!-- Ruang Pengesahan & Tandatangan -->
    <table class="footer-signatures">
        <tr>
            <td>
                <div style="font-size: 8px; color: #64748b; margin-bottom: 40px;">
                    Report System Auth: <br>
                    <strong>Generated via Manager Operations Portal</strong>
                </div>
            </td>
            <td style="text-align: right;">
                <div style="display: inline-block;">
                    <div style="height: 35px;"></div>
                    <div class="sig-box">
                        Authorized Manager Signature
                    </div>
                </div>
            </td>
        </tr>
    </table>

    <!-- Notis Kerahsiaan Footer -->
    <div class="confidential-notice">
        CONFIDENTIAL & PROPRIETARY — VISION CONCEPT EYEWEAR SYSTEM REPORT — GENERATED ON {{ strtoupper(now()->format('d-M-Y H:i:s')) }}
    </div>

</body>
</html>
