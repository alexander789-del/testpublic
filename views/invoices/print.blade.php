<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Invoice #{{ str_pad($invoice->id,4,'0',STR_PAD_LEFT) }} — {{ $invoice->month }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * { margin:0; padding:0; box-sizing:border-box; }

        body {
            font-family: 'Inter', 'Segoe UI', Arial, sans-serif;
            font-size: 12px;
            color: #1e293b;
            background: #f0f2f5;
            padding: 20px 16px;
        }

        .print-bar {
            max-width: 680px;
            margin: 0 auto 12px;
            display: flex;
            justify-content: flex-end;
            gap: 8px;
        }
        .print-bar a, .print-bar button {
            padding: 7px 16px;
            border-radius: 7px;
            font-size: 12px;
            font-weight: 500;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 5px;
            border: none;
        }
        .btn-back  { background:#fff; color:#475569; border:1px solid #e2e8f0; }
        .btn-print { background:#0f172a; color:#fff; }

        .page {
            background: #fff;
            max-width: 680px;
            margin: 0 auto;
            border-radius: 6px;
            overflow: hidden;
            border: 1px solid #e2e8f0;
        }

        .accent-bar { height: 4px; background: #0f172a; }

        /* ── Header ── */
        .inv-header {
            padding: 16px 28px 12px;
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            border-bottom: 1px solid #f1f5f9;
        }
        .brand-name { font-size: 15px; font-weight: 700; color: #0f172a; }
        .brand-tag  { font-size: 9px; color: #94a3b8; text-transform: uppercase; letter-spacing: .1em; margin-top: 2px; }
        .brand-info { font-size: 10px; color: #64748b; margin-top: 6px; line-height: 1.7; }
        .inv-meta { text-align: right; }
        .inv-meta .lbl  { font-size: 9px; color: #94a3b8; text-transform: uppercase; letter-spacing: .1em; }
        .inv-meta .num  { font-size: 22px; font-weight: 700; color: #0f172a; letter-spacing: -1px; margin-top: 1px; }
        .inv-meta .date { font-size: 10px; color: #64748b; margin-top: 4px; line-height: 1.7; }

        /* ── Status ── */
        .status-wrap { padding: 8px 28px; }
        .status-pill {
            display: inline-flex; align-items: center; gap: 6px;
            padding: 5px 12px; border-radius: 5px;
            font-size: 11px; font-weight: 600;
        }
        .status-paid   { background:#f0fdf4; color:#166534; border:1px solid #bbf7d0; }
        .status-unpaid { background:#fef2f2; color:#991b1b; border:1px solid #fecaca; }
        .status-dot { width:6px; height:6px; border-radius:50%; }
        .dot-paid   { background:#16a34a; }
        .dot-unpaid { background:#dc2626; }

        /* ── Parties ── */
        .parties {
            display: grid; grid-template-columns: 1fr 1fr;
            gap: 10px; padding: 8px 28px 12px;
        }
        .party-box { background:#f8fafc; border-radius:6px; padding:10px 14px; }
        .party-lbl  { font-size:8px; font-weight:700; text-transform:uppercase; letter-spacing:.12em; color:#94a3b8; margin-bottom:5px; }
        .party-name { font-size:13px; font-weight:700; color:#0f172a; }
        .party-info { font-size:10px; color:#64748b; margin-top:3px; line-height:1.7; }

        /* ── Items ── */
        .items { padding: 0 28px; }

        .section-head {
            display:flex; align-items:center; gap:6px;
            margin: 10px 0 4px;
        }
        .section-icon {
            width:18px; height:18px; border-radius:50%;
            display:flex; align-items:center; justify-content:center;
            font-size:10px;
        }
        .section-lbl {
            font-size:8px; font-weight:700;
            text-transform:uppercase; letter-spacing:.1em; color:#94a3b8;
        }

        table.lt {
            width:100%; border-collapse:collapse; margin-bottom:2px;
        }
        table.lt thead th {
            font-size:8px; color:#94a3b8; text-transform:uppercase;
            letter-spacing:.06em; font-weight:600;
            padding:4px 0; border-bottom:1px solid #e2e8f0;
        }
        table.lt thead th:last-child { text-align:right; }
        table.lt tbody td {
            padding:6px 0; border-bottom:1px solid #f8fafc; vertical-align:top;
        }
        table.lt tbody td:last-child {
            text-align:right; font-weight:600; color:#0f172a;
            white-space:nowrap; padding-left:10px;
        }
        .td-main { font-size:12px; color:#1e293b; font-weight:500; }
        .td-sub  { font-size:9.5px; color:#94a3b8; margin-top:1px; line-height:1.5; }
        .td-mid  { font-size:10px; color:#64748b; }

        /* ── Divider ── */
        .dash-line { border:none; border-top:1px dashed #e2e8f0; margin:10px 28px; }

        /* ── Totals ── */
        .totals { padding: 0 28px; }
        .subtotals-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 6px;
            margin-bottom: 10px;
        }
        .sub-cell {
            background: #f8fafc;
            border-radius: 6px;
            padding: 7px 10px;
        }
        .sub-cell .sc-lbl { font-size:9px; color:#94a3b8; margin-bottom:2px; }
        .sub-cell .sc-val { font-size:13px; font-weight:700; color:#0f172a; }

        .total-bar {
            display:flex; justify-content:space-between; align-items:center;
            background:#0f172a; color:white;
            padding:12px 20px; border-radius:8px;
        }
        .tl-label { font-size:10px; opacity:.6; margin-bottom:1px; }
        .tl-month { font-size:12px; font-weight:600; }
        .tl-amt   { font-size:26px; font-weight:700; letter-spacing:-1px; }

        .utility-note {
            text-align:center; font-size:9.5px; color:#94a3b8; margin-top:7px;
        }

        /* ── Signature ── */
        .sig-area {
            display:grid; grid-template-columns:1fr 1fr;
            gap:16px; margin:12px 28px 0;
            padding-top:12px; border-top:1px solid #f1f5f9;
        }
        .sig-box { text-align:center; }
        .sig-line { border-bottom:1px solid #cbd5e1; height:30px; margin-bottom:4px; }
        .sig-lbl  { font-size:9px; color:#94a3b8; text-transform:uppercase; letter-spacing:.08em; }

        /* ── Footer ── */
        .inv-footer {
            display:flex; justify-content:space-between; align-items:center;
            padding:10px 28px; background:#f8fafc;
            border-top:1px solid #e2e8f0; margin-top:12px;
        }
        .footer-thanks { font-size:13px; font-weight:700; color:#0f172a; }
        .footer-sub    { font-size:10px; color:#94a3b8; margin-top:1px; }
        .footer-right  { text-align:right; font-size:9.5px; color:#94a3b8; line-height:1.7; }

        .watermark {
            text-align:center; padding:5px;
            font-size:8.5px; color:#cbd5e1;
            letter-spacing:.12em; text-transform:uppercase;
        }

        /* ── PRINT ── */
        @media print {
            @page {
                size: A4;
                margin: 10mm 12mm;
            }
            body { background:white; padding:0; font-size:11px; }
            .page { border:none; border-radius:0; box-shadow:none; max-width:100%; }
            .print-bar { display:none !important; }
            .accent-bar,
            .status-pill,
            .total-bar,
            .party-box,
            .sub-cell { -webkit-print-color-adjust:exact; print-color-adjust:exact; }
        }
    </style>
</head>
<body>

<div class="print-bar">
    <a href="{{ route('invoices.show', $invoice) }}" class="btn-back">&#8592; Back</a>
    <button class="btn-print" onclick="window.print()">&#128438; Print / Save PDF</button>
</div>

<div class="page">
    <div class="accent-bar"></div>

    {{-- Header --}}
    <div class="inv-header">
        <div>
            <div class="brand-name">Room Rental</div>
            <div class="brand-tag">Management System</div>
            <div class="brand-info">
                Phnom Penh, Cambodia<br>
                Tel: 012 345 678
            </div>
        </div>
        <div class="inv-meta">
            <div class="lbl">Invoice</div>
            <div class="num">#{{ str_pad($invoice->id, 4, '0', STR_PAD_LEFT) }}</div>
            <div class="date">
                Billing: <strong>{{ $invoice->month }}</strong><br>
                Issued: {{ $invoice->created_at->format('d M Y') }}
            </div>
        </div>
    </div>

    {{-- Status --}}
    <div class="status-wrap">
        <div class="status-pill {{ $invoice->status === 'paid' ? 'status-paid' : 'status-unpaid' }}">
            <div class="status-dot {{ $invoice->status === 'paid' ? 'dot-paid' : 'dot-unpaid' }}"></div>
            @if($invoice->status === 'paid')
                Payment received &mdash; {{ $invoice->paid_date?->format('d M Y') }}
            @else
                Payment pending &mdash; please pay by end of month
            @endif
        </div>
    </div>

    {{-- Parties --}}
    <div class="parties">
        <div class="party-box">
            <div class="party-lbl">Bill To</div>
            <div class="party-name">{{ $invoice->tenant->name }}</div>
            <div class="party-info">
                @if($invoice->tenant->phone)
                    Tel: {{ $invoice->tenant->phone }}<br>
                @endif
                @if($invoice->tenant->national_id)
                    ID: {{ $invoice->tenant->national_id }}<br>
                @endif
                @if($invoice->tenant->nationality)
                    Nationality: {{ $invoice->tenant->nationality }}<br>
                @endif
                Move-in: {{ $invoice->tenant->move_in_date->format('d M Y') }}
            </div>
        </div>
        <div class="party-box">
            <div class="party-lbl">Property</div>
            <div class="party-name">{{ $invoice->room->name }}</div>
            <div class="party-info">
                Monthly rent: ${{ number_format($invoice->monthly_fee, 2) }}<br>
                Rate: $1 = {{ number_format($invoice->exchange_rate) }} &#6107;<br>
                Period: {{ $invoice->month }}
            </div>
        </div>
    </div>

    {{-- Line Items --}}
    <div class="items">

        {{-- Rent --}}
        <div class="section-head">
            <div class="section-icon" style="background:#eff6ff">&#127968;</div>
            <div class="section-lbl">Monthly Rent</div>
        </div>
        <table class="lt">
            <thead><tr><th>Description</th><th>Amount</th></tr></thead>
            <tbody>
                <tr>
                    <td>
                        <div class="td-main">Room fee &mdash; {{ $invoice->month }}</div>
                        <div class="td-sub">{{ $invoice->room->name }}</div>
                    </td>
                    <td>${{ number_format($invoice->monthly_fee, 2) }}</td>
                </tr>
            </tbody>
        </table>

        {{-- Water --}}
        <div class="section-head">
            <div class="section-icon" style="background:#eff6ff">&#128167;</div>
            <div class="section-lbl">Water</div>
        </div>
        <table class="lt">
            <thead>
                <tr>
                    <th>Description</th>
                    <th>Calculation</th>
                    <th>Amount</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    @if($invoice->water_mode === 'fixed')
                    <td>
                        <div class="td-main">Water &mdash; fixed fee</div>
                        <div class="td-sub">Flat monthly rate</div>
                    </td>
                    <td class="td-mid">—</td>
                    @else
                    <td>
                        <div class="td-main">Water consumption</div>
                        <div class="td-sub">
                            Meter: {{ $invoice->prev_water }} &#8594; {{ $invoice->curr_water }} m&#179;
                            &nbsp;&middot;&nbsp; Used: {{ $invoice->water_used }} m&#179;
                        </div>
                    </td>
                    <td class="td-mid">
                        {{ $invoice->water_used }} &times; {{ number_format($invoice->water_rate) }}&#6107;
                        = {{ number_format($invoice->water_fee_riel) }}&#6107;
                    </td>
                    @endif
                    <td>${{ number_format($invoice->water_fee_usd, 2) }}</td>
                </tr>
            </tbody>
        </table>

        {{-- Electric --}}
        <div class="section-head">
            <div class="section-icon" style="background:#fffbeb">&#9889;</div>
            <div class="section-lbl">Electric</div>
        </div>
        <table class="lt">
            <thead>
                <tr>
                    <th>Description</th>
                    <th>Calculation</th>
                    <th>Amount</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        <div class="td-main">Electric consumption</div>
                        <div class="td-sub">
                            Meter: {{ $invoice->prev_electric }} &#8594; {{ $invoice->curr_electric }} kWh
                            &nbsp;&middot;&nbsp; Used: {{ $invoice->electric_used }} kWh
                        </div>
                    </td>
                    <td class="td-mid">
                        {{ $invoice->electric_used }} &times; {{ number_format($invoice->electric_rate) }}&#6107;
                        = {{ number_format($invoice->electric_fee_riel) }}&#6107;
                    </td>
                    <td>${{ number_format($invoice->electric_fee_usd, 2) }}</td>
                </tr>
            </tbody>
        </table>

        {{-- Extra --}}
        @if($invoice->extra_fee > 0)
        <div class="section-head">
            <div class="section-icon" style="background:#f0fdf4">&#43;</div>
            <div class="section-lbl">Extra Charges</div>
        </div>
        <table class="lt">
            <thead><tr><th>Description</th><th>Amount</th></tr></thead>
            <tbody>
                <tr>
                    <td><div class="td-main">{{ $invoice->extra_fee_note ?? 'Extra fee' }}</div></td>
                    <td>${{ number_format($invoice->extra_fee, 2) }}</td>
                </tr>
            </tbody>
        </table>
        @endif

    </div>

    <hr class="dash-line">

    {{-- Totals --}}
    <div class="totals">

        {{-- Subtotals as grid cards --}}
        <div class="subtotals-grid">
            <div class="sub-cell">
                <div class="sc-lbl">Room</div>
                <div class="sc-val">${{ number_format($invoice->monthly_fee, 2) }}</div>
            </div>
            <div class="sub-cell">
                <div class="sc-lbl">Water</div>
                <div class="sc-val">${{ number_format($invoice->water_fee_usd, 2) }}</div>
            </div>
            <div class="sub-cell">
                <div class="sc-lbl">Electric</div>
                <div class="sc-val">${{ number_format($invoice->electric_fee_usd, 2) }}</div>
            </div>
            <div class="sub-cell">
                <div class="sc-lbl">Extra</div>
                <div class="sc-val">${{ number_format($invoice->extra_fee, 2) }}</div>
            </div>
        </div>

        <div class="total-bar">
            <div>
                <div class="tl-label">Total amount due</div>
                <div class="tl-month">{{ $invoice->month }}</div>
            </div>
            <div class="tl-amt">${{ number_format($invoice->total_usd, 2) }}</div>
        </div>

        <div class="utility-note">
            Utilities: {{ number_format($invoice->water_fee_riel) }}&#6107;
            + {{ number_format($invoice->electric_fee_riel) }}&#6107;
            = {{ number_format($invoice->water_fee_riel + $invoice->electric_fee_riel) }}&#6107;
            &nbsp;&middot;&nbsp; Rate: $1 = {{ number_format($invoice->exchange_rate) }}&#6107;
        </div>
    </div>

    {{-- Signatures --}}
    <div class="sig-area">
        <div class="sig-box">
            <div class="sig-line"></div>
            <div class="sig-lbl">Tenant Signature</div>
        </div>
        <div class="sig-box">
            <div class="sig-line"></div>
            <div class="sig-lbl">Landlord Signature</div>
        </div>
    </div>

    {{-- Footer --}}
    <div class="inv-footer">
        <div>
            <div class="footer-thanks">Thank you! &#x1F64F;</div>
            <div class="footer-sub">Please pay by end of {{ $invoice->month }}</div>
        </div>
        <div class="footer-right">
            Generated: {{ now()->format('d M Y, h:i A') }}<br>
            Invoice #{{ str_pad($invoice->id, 4, '0', STR_PAD_LEFT) }}
        </div>
    </div>

    <div class="watermark">Room Rental Management System &mdash; Phnom Penh, Cambodia</div>
</div>

</body>
</html>