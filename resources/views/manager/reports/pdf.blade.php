<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Report #{{ $report->id }}</title>
    <style>
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; color: #334155; line-height: 1.5; margin: 0; padding: 20px; }
        .header { border-bottom: 2px solid #e2e8f0; padding-bottom: 15px; margin-bottom: 20px; }
        .header h2 { margin: 0; color: #1e293b; font-size: 20px; }
        .header p { margin: 5px 0 0; color: #64748b; font-size: 12px; }
        .grid { width: 100%; margin-bottom: 20px; border-collapse: collapse; }
        .grid td { width: 50%; padding: 8px 0; vertical-align: top; }
        .label { font-size: 10px; text-transform: uppercase; color: #94a3b8; font-weight: bold; display: block; }
        .value { font-size: 14px; font-weight: bold; color: #1e293b; margin-top: 2px; }
        .summary-box { background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 15px; margin-bottom: 10px; }
        .summary-title { font-size: 11px; font-weight: bold; color: #64748b; text-transform: uppercase; }
        .summary-value { font-size: 18px; font-weight: bold; color: #0f172a; margin-top: 5px; }
    </style>
</head>
<body>

    <div class="header">
        <h2>Report Details #{{ $report->id }}</h2>
        <p>Detailed information and performance summary for this report.</p>
    </div>

    <table class="grid">
        <tr>
            <td>
                <span class="label">Report Type</span>
                <span class="value">{{ $report->report_type }}</span>
            </td>
            <td>
                <span class="label">Generated Date</span>
                <span class="value">{{ $report->created_at->format('d M Y, h:i A') }}</span>
            </td>
        </tr>
        <tr>
            <td>
                <span class="label">Start Date</span>
                <span class="value">{{ $report->start_date }}</span>
            </td>
            <td>
                <span class="label">End Date</span>
                <span class="value">{{ $report->end_date }}</span>
            </td>
        </tr>
    </table>

    <h3 style="font-size: 12px; text-transform: uppercase; color: #94a3b8; border-bottom: 1px solid #f1f5f9; padding-bottom: 5px; margin-top: 30px;">Statistics & Summary</h3>

    <div class="summary-box">
        <div class="summary-title">Total Sales</div>
        <div class="summary-value">RM {{ number_format($report->data_summary['total_sales'] ?? 0, 2) }}</div>
    </div>

    <div class="summary-box">
        <div class="summary-title">Total Orders</div>
        <div class="summary-value">{{ $report->data_summary['total_orders'] ?? 0 }} Orders</div>
    </div>

</body>
</html>
