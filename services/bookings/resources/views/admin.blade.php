<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Booking Admin (Local)</title>
    <style>
        :root {
            --bg: #f9fafb;
            --panel: #ffffff;
            --border: #e5e7eb;
            --text: #111827;
            --muted: #6b7280;
            --muted-2: #374151;
            --primary: #111827;
            --focus: rgba(17, 24, 39, 0.12);
        }

        body {
            font-family: system-ui, -apple-system, Segoe UI, Roboto, Arial, sans-serif;
            margin: 0;
            background: var(--bg);
            color: var(--text);
        }

        header {
            position: sticky;
            top: 0;
            background: rgba(255, 255, 255, 0.92);
            backdrop-filter: saturate(180%) blur(10px);
            border-bottom: 1px solid var(--border);
            z-index: 10;
        }

        .nav {
            max-width: 1100px;
            margin: 0 auto;
            padding: 12px 16px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
        }

        .brand {
            display: flex;
            flex-direction: column;
            gap: 2px;
        }

        .brandTitle {
            font-weight: 750;
            letter-spacing: -0.01em;
            line-height: 1.1;
        }

        .brandSub {
            color: var(--muted);
            font-size: 12px;
        }

        .muted {
            color: var(--muted);
            font-size: 12px;
        }

        .mono {
            font-family: ui-monospace, SFMono-Regular, Menlo, Consolas, monospace;
        }

        code {
            background: #f3f4f6;
            border: 1px solid var(--border);
            padding: 2px 6px;
            border-radius: 999px;
        }

        main {
            max-width: 1100px;
            margin: 0 auto;
            padding: 20px 16px 32px;
        }

        .pageTitle {
            display: flex;
            flex-direction: column;
            gap: 6px;
            margin: 8px 0 12px;
        }

        h1 {
            font-size: 22px;
            font-weight: 750;
            letter-spacing: -0.015em;
            margin: 0;
        }

        h2 {
            font-size: 13px;
            font-weight: 700;
            margin: 0;
            letter-spacing: 0.01em;
            text-transform: uppercase;
            color: var(--muted-2);
        }

        .card {
            border: 1px solid var(--border);
            border-radius: 14px;
            padding: 14px;
            background: var(--panel);
            margin-top: 14px;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
        }

        .cardHead {
            display: flex;
            flex-direction: column;
            gap: 6px;
            margin-bottom: 12px;
        }

        .help {
            color: var(--muted);
            font-size: 13px;
            line-height: 1.5;
        }

        label {
            display: block;
            font-size: 12px;
            color: var(--muted-2);
            margin-bottom: 6px;
        }

        input,
        select {
            width: 100%;
            box-sizing: border-box;
            padding: 10px 12px;
            border: 1px solid #d1d5db;
            border-radius: 10px;
            background: #ffffff;
            color: var(--text);
            outline: none;
        }

        input:focus,
        select:focus {
            border-color: #9ca3af;
            box-shadow: 0 0 0 4px var(--focus);
        }

        .row {
            display: grid;
            grid-template-columns: 1fr;
            gap: 12px;
        }

        @media (min-width: 860px) {
            .row {
                grid-template-columns: 1fr 1fr 1fr;
            }
        }

        .actions {
            margin-top: 14px;
            display: flex;
            gap: 10px;
            align-items: center;
            flex-wrap: wrap;
        }

        button {
            padding: 10px 14px;
            border: 1px solid var(--primary);
            background: var(--primary);
            color: #fff;
            border-radius: 10px;
            cursor: pointer;
            font-weight: 650;
            letter-spacing: -0.01em;
        }

        button:hover {
            filter: brightness(0.97);
        }

        button:active {
            transform: translateY(1px);
        }

        button:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            transform: none;
        }

        .status {
            display: inline-flex;
            align-items: center;
            height: 28px;
            padding: 0 10px;
            border-radius: 999px;
            border: 1px solid var(--border);
            background: #fff;
            color: var(--muted);
            font-size: 12px;
        }

        .notice {
            margin-top: 12px;
            padding: 12px;
            border-radius: 12px;
            border: 1px solid var(--border);
            background: #f9fafb;
            font-size: 13px;
            line-height: 1.5;
        }

        .notice.ok {
            border-color: #86efac;
            background: #f0fdf4;
        }

        .notice.err {
            border-color: #fecaca;
            background: #fef2f2;
        }

        .calendar {
            margin-top: 12px;
            border: 1px solid var(--border);
            border-radius: 14px;
            background: #fff;
            overflow: hidden;
        }

        .calHeader {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            padding: 12px;
            border-bottom: 1px solid var(--border);
            background: #f9fafb;
        }

        .calTitle {
            font-weight: 750;
            letter-spacing: -0.01em;
        }

        .legend {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            color: var(--muted);
            font-size: 12px;
        }

        .legend span {
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .dot {
            width: 10px;
            height: 10px;
            border-radius: 999px;
            border: 1px solid var(--border);
            background: #fff;
        }

        .dot.ok { border-color: #86efac; background: #f0fdf4; }
        .dot.err { border-color: #fecaca; background: #fef2f2; }
        .dot.missing { border-color: var(--border); background: #f3f4f6; }

        .calGrid {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
        }

        .dow {
            padding: 10px 12px;
            font-size: 12px;
            color: var(--muted);
            font-weight: 700;
            background: #fff;
            border-bottom: 1px solid var(--border);
        }

        .day {
            min-height: 110px;
            padding: 10px 12px;
            border-right: 1px solid var(--border);
            border-bottom: 1px solid var(--border);
        }

        .day:nth-child(7n) { border-right: none; }

        .dayNum {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 8px;
            font-variant-numeric: tabular-nums;
        }

        .badge {
            display: inline-flex;
            align-items: center;
            height: 20px;
            padding: 0 8px;
            border-radius: 999px;
            border: 1px solid var(--border);
            font-size: 12px;
            color: var(--muted-2);
            background: #fff;
        }

        .badge.ok { border-color: #86efac; background: #f0fdf4; }
        .badge.err { border-color: #fecaca; background: #fef2f2; }
        .badge.missing { border-color: var(--border); background: #f3f4f6; color: var(--muted); }

        .kv {
            display: grid;
            grid-template-columns: auto 1fr;
            gap: 6px 10px;
            font-size: 12px;
            color: var(--muted-2);
        }

        .k { color: var(--muted); }

        .navPill {
            text-decoration: none;
            padding: 6px 10px;
            border-radius: 999px;
            border: 1px solid var(--border);
            background: #fff;
            color: var(--muted-2);
        }

        .navPill.active {
            background: #f3f4f6;
            color: var(--text);
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            border: 1px solid var(--border);
            border-radius: 12px;
            overflow: hidden;
        }

        .table th,
        .table td {
            padding: 10px 12px;
            border-bottom: 1px solid var(--border);
            vertical-align: top;
            font-size: 13px;
        }

        .table th {
            text-align: left;
            background: #f9fafb;
            color: var(--muted-2);
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.02em;
        }

        .table tr:last-child td {
            border-bottom: none;
        }

        .link {
            color: var(--text);
            text-decoration: underline;
            text-underline-offset: 2px;
        }

        .split {
            display: grid;
            grid-template-columns: 1fr;
            gap: 14px;
        }

        @media (min-width: 860px) {
            .split {
                grid-template-columns: 1fr 1fr;
            }
        }

        .picker {
            margin-top: 12px;
            border: 1px solid var(--border);
            border-radius: 14px;
            background: #fff;
            overflow: hidden;
        }

        .pickerHead {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            padding: 10px 12px;
            border-bottom: 1px solid var(--border);
            background: #f9fafb;
        }

        .pickerTitle {
            font-weight: 750;
            letter-spacing: -0.01em;
        }

        .pickerBtns {
            display: flex;
            gap: 8px;
            align-items: center;
        }

        .btnGhost {
            padding: 8px 10px;
            border: 1px solid var(--border);
            background: #fff;
            color: var(--text);
            border-radius: 10px;
            cursor: pointer;
            font-weight: 650;
        }

        .btnGhost:hover {
            background: #f9fafb;
        }

        .pickerGrid {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            gap: 8px;
            padding: 12px;
        }

        .pickerDow {
            font-size: 11px;
            color: var(--muted);
            text-transform: uppercase;
            letter-spacing: 0.02em;
            text-align: center;
        }

        .pickerDay {
            height: 38px;
            border-radius: 10px;
            border: 1px solid var(--border);
            background: #fff;
            color: var(--text);
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 13px;
        }

        .pickerDay:hover {
            background: #f9fafb;
        }

        .pickerDay.muted {
            color: #9ca3af;
            background: #fafafa;
        }

        .pickerDay.selected {
            border-color: #9ca3af;
            box-shadow: 0 0 0 4px var(--focus);
            color: var(--text);
        }

        .pickerDay.inRange {
            background: #f3f4f6;
        }

        .pickerFoot {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            padding: 10px 12px;
            border-top: 1px solid var(--border);
            background: #fff;
        }

        .pickerHelp {
            color: var(--muted);
            font-size: 12px;
        }
    </style>
</head>
<body>
@php($isAdmin = request()->is('admin*'))
<header>
    <nav class="nav">
        <div style="display:flex; align-items:center; gap: 14px;">
            <div class="brand">
                <div class="brandTitle">Booking (Local)</div>
                <div class="brandSub">Admin view</div>
            </div>

            <div style="display:flex; align-items:center; gap: 8px;">
                <a href="./" class="navPill {{ $isAdmin ? '' : 'active' }}">User</a>
                <a href="./admin" class="navPill {{ $isAdmin ? 'active' : '' }}">Admin</a>
            </div>
        </div>

        <span class="muted">Gateway: <code class="mono">http://localhost:8080</code></span>
    </nav>
</header>

<main>
    <div class="pageTitle">
        <h1>Inventory Calendar (Admin)</h1>
        <div class="muted">Search by location (property) and room type to see per-day totals.</div>
    </div>

    <div class="split">
        <section class="card">
            <div class="cardHead">
                <h2>Set Inventory</h2>
                <div class="help">Writes to Availability. Use this to add rooms (set <span class="mono">total</span>) for a date or date range.</div>
            </div>

            <div class="row">
                <div>
                    <label>Location (Property ID)</label>
                    <input id="i_property_id" type="number" min="1" value="1" />
                </div>
                <div>
                    <label>Room Type</label>
                    <select id="i_room_type_code">
                        <option value="STD">STD (Standard)</option>
                        <option value="DLX">DLX (Deluxe)</option>
                        <option value="STE">STE (Suite)</option>
                    </select>
                </div>
                <div>
                    <label>Total rooms (per night)</label>
                    <input id="i_total" type="number" min="0" value="5" />
                </div>
            </div>

            <input id="i_date" type="hidden" />
            <input id="i_from" type="hidden" />
            <input id="i_to" type="hidden" />

            <div class="notice" style="margin-top:12px; background:#fff;">
                <div style="display:flex; justify-content:space-between; gap:10px; align-items:center; flex-wrap:wrap;">
                    <div>
                        <div style="font-weight:750; letter-spacing:-0.01em;">Selected dates</div>
                        <div class="muted mono" id="inventorySelected">—</div>
                    </div>
                    <button id="btnTogglePicker" class="btnGhost" type="button">Pick dates on calendar</button>
                </div>
                <div class="muted" style="margin-top:6px;">Click start then end (same day = single night). Then click <span class="mono">Apply</span>.</div>
            </div>

            <div id="inventoryPicker" class="picker" style="display:none;">
                <div class="pickerHead">
                    <div>
                        <div class="pickerTitle" id="pickerTitle">Dates</div>
                        <div class="muted" id="pickerSub">Select a start and end date</div>
                    </div>
                    <div class="pickerBtns">
                        <button id="btnPickerPrev" class="btnGhost" type="button">Prev</button>
                        <button id="btnPickerNext" class="btnGhost" type="button">Next</button>
                    </div>
                </div>

                <div class="pickerGrid" id="pickerGrid">
                    <div class="pickerDow">Sun</div>
                    <div class="pickerDow">Mon</div>
                    <div class="pickerDow">Tue</div>
                    <div class="pickerDow">Wed</div>
                    <div class="pickerDow">Thu</div>
                    <div class="pickerDow">Fri</div>
                    <div class="pickerDow">Sat</div>
                </div>

                <div class="pickerFoot">
                    <div class="pickerHelp" id="pickerHelp">Start: — • End: —</div>
                    <div style="display:flex; gap:8px; align-items:center;">
                        <button id="btnPickerClear" class="btnGhost" type="button">Clear</button>
                        <button id="btnPickerApply" type="button">Apply</button>
                    </div>
                </div>
            </div>

            <div class="actions">
                <button id="btnInventoryUpsert" type="button">Save inventory</button>
            </div>

            <div id="inventoryNotice" class="notice" style="display:none"></div>
        </section>

        <section class="card">
            <div class="cardHead">
                <h2>Search Bookings</h2>
                <div class="help">Reads from Bookings. Search by date window (inclusive) to see who booked what.</div>
            </div>

            <div class="row">
                <div>
                    <label>Location (Property ID)</label>
                    <input id="b_property_id" type="number" min="1" value="1" />
                </div>
                <div>
                    <label>Date from</label>
                    <input id="b_from" type="date" />
                </div>
                <div>
                    <label>Date to (inclusive)</label>
                    <input id="b_to" type="date" />
                </div>
            </div>

            <div class="row" style="margin-top:12px;">
                <div>
                    <label>Room Type (optional)</label>
                    <select id="b_room_type_code">
                        <option value="">Any</option>
                        <option value="STD">STD (Standard)</option>
                        <option value="DLX">DLX (Deluxe)</option>
                        <option value="STE">STE (Suite)</option>
                    </select>
                </div>
                <div>
                    <label>Status (optional)</label>
                    <select id="b_status">
                        <option value="">Any</option>
                        <option value="confirmed">confirmed</option>
                        <option value="cancelled">cancelled</option>
                    </select>
                </div>
                <div>
                    <label>Guest email contains (optional)</label>
                    <input id="b_guest_email" type="text" placeholder="guest@example.com" />
                </div>
            </div>

            <div class="actions">
                <button id="btnBookingSearch" type="button">Search bookings</button>
            </div>

            <div id="bookingNotice" class="notice" style="display:none"></div>

            <div id="bookingResults" style="display:none; margin-top:12px;">
                <table class="table">
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>Status</th>
                        <th>Property</th>
                        <th>Room</th>
                        <th>Dates</th>
                        <th>Guest</th>
                    </tr>
                    </thead>
                    <tbody id="bookingRows"></tbody>
                </table>
                <div class="muted" id="bookingNext" style="margin-top:10px;"></div>
            </div>
        </section>
    </div>

    <section class="card">
        <div class="cardHead">
            <h2>Search</h2>
            <div class="help">This reads from Availability and shows <span class="mono">total / held / booked / available</span> for each day in the month.</div>
        </div>

        <div class="row">
            <div>
                <label>Location (Property ID)</label>
                <input id="a_property_id" type="number" min="1" value="1" />
            </div>
            <div>
                <label>Room Type</label>
                <select id="a_room_type_code">
                    <option value="STD">STD (Standard)</option>
                    <option value="DLX">DLX (Deluxe)</option>
                    <option value="STE">STE (Suite)</option>
                </select>
            </div>
            <div>
                <label>Month</label>
                <input id="a_month" type="month" />
            </div>
        </div>

        <div class="actions">
            <button id="btnAdminSearch" type="button">Search</button>
        </div>

        <div id="adminNotice" class="notice" style="display:none"></div>

        <div id="calendar" class="calendar" style="display:none">
            <div class="calHeader">
                <div>
                    <div class="calTitle" id="calTitle">Month</div>
                    <div class="muted" id="calSub">Property • Room type</div>
                </div>
                <div class="legend">
                    <span><span class="dot ok"></span>Available</span>
                    <span><span class="dot err"></span>Sold out</span>
                    <span><span class="dot missing"></span>No inventory</span>
                </div>
            </div>

            <div class="calGrid" id="calGrid">
                <div class="dow">Sun</div>
                <div class="dow">Mon</div>
                <div class="dow">Tue</div>
                <div class="dow">Wed</div>
                <div class="dow">Thu</div>
                <div class="dow">Fri</div>
                <div class="dow">Sat</div>
            </div>
        </div>
    </section>
</main>

<script>
    function showNotice(el, kind, message) {
        el.className = `notice ${kind}`;
        el.textContent = message;
        el.style.display = 'block';
    }

    function hide(el) {
        el.style.display = 'none';
    }

    function wait(ms) {
        return new Promise((resolve) => setTimeout(resolve, ms));
    }

    async function safeReadBody(resp) {
        const contentType = (resp.headers.get('content-type') || '').toLowerCase();
        if (contentType.includes('application/json')) {
            return await resp.json().catch(() => null);
        }
        return await resp.text().catch(() => null);
    }

    function friendlyServiceDown(serviceName) {
        return `${serviceName} service looks stopped or unreachable. Start the container and try again.`;
    }

    function friendlyFromHttp(serviceName, status, body) {
        if (status === 502 || status === 503 || status === 504) {
            return friendlyServiceDown(serviceName);
        }
        if (status >= 500) {
            return friendlyServiceDown(serviceName);
        }
        if (status === 422) {
            return 'Please check your inputs.';
        }
        if (status === 404) {
            return 'Route not found (check the gateway paths).';
        }

        if (typeof body === 'object' && body && body.message) {
            return String(body.message);
        }
        return `Request failed (HTTP ${status}).`;
    }

    function toIsoDateUTC(d) {
        return d.toISOString().slice(0, 10);
    }

    function parseIsoDateToUTC(iso) {
        const [y, m, d] = String(iso || '').split('-').map((x) => Number(x));
        if (!y || !m || !d) return null;
        return new Date(Date.UTC(y, m - 1, d));
    }

    function formatMonthLabelUTC(d) {
        const y = d.getUTCFullYear();
        const m = String(d.getUTCMonth() + 1).padStart(2, '0');
        return `${y}-${m}`;
    }

    function startOfMonthUTC(d) {
        return new Date(Date.UTC(d.getUTCFullYear(), d.getUTCMonth(), 1));
    }

    function addMonthsUTC(d, delta) {
        return new Date(Date.UTC(d.getUTCFullYear(), d.getUTCMonth() + delta, 1));
    }

    function daysInMonthUTCByMonthStart(monthStart) {
        const y = monthStart.getUTCFullYear();
        const m = monthStart.getUTCMonth();
        const end = new Date(Date.UTC(y, m + 1, 1));
        return Math.round((end - monthStart) / (24 * 60 * 60 * 1000));
    }

    function setDefaultDates() {
        const now = new Date();
        const from = new Date(Date.UTC(now.getFullYear(), now.getMonth(), now.getDate()));
        const to = new Date(Date.UTC(now.getFullYear(), now.getMonth(), now.getDate() + 30));

        document.getElementById('b_from').value = toIsoDateUTC(from);
        document.getElementById('b_to').value = toIsoDateUTC(to);

        // Default inventory selection to range.
        document.getElementById('i_date').value = '';
        document.getElementById('i_from').value = toIsoDateUTC(from);
        document.getElementById('i_to').value = toIsoDateUTC(to);
    }

    function updateInventorySelectedLabel() {
        const labelEl = document.getElementById('inventorySelected');
        const date = String(document.getElementById('i_date').value || '');
        const from = String(document.getElementById('i_from').value || '');
        const to = String(document.getElementById('i_to').value || '');

        if (date) {
            labelEl.textContent = date;
            return;
        }

        if (from && to) {
            labelEl.textContent = `${from} → ${to}`;
            return;
        }

        labelEl.textContent = '—';
    }

    // ---- Inventory range picker (in-page calendar) ----
    let pickerMonthStart = null; // Date UTC at first day of month
    let pickerStartIso = '';
    let pickerEndIso = '';

    function setPickerHelp() {
        const help = document.getElementById('pickerHelp');
        help.textContent = `Start: ${pickerStartIso || '—'} • End: ${pickerEndIso || '—'}`;
    }

    function setPickerTitle() {
        const title = document.getElementById('pickerTitle');
        title.textContent = `Pick dates • ${formatMonthLabelUTC(pickerMonthStart)}`;
    }

    function isBetweenInclusive(iso, startIso, endIso) {
        if (!iso || !startIso || !endIso) return false;
        return iso >= startIso && iso <= endIso;
    }

    function renderPicker() {
        setPickerTitle();
        setPickerHelp();

        const grid = document.getElementById('pickerGrid');
        // Keep first 7 DOW items
        while (grid.children.length > 7) {
            grid.removeChild(grid.lastChild);
        }

        const firstDow = pickerMonthStart.getUTCDay();
        const totalDays = daysInMonthUTCByMonthStart(pickerMonthStart);

        // pad
        for (let i = 0; i < firstDow; i++) {
            const pad = document.createElement('div');
            pad.className = 'pickerDay muted';
            pad.textContent = '';
            pad.style.cursor = 'default';
            grid.appendChild(pad);
        }

        for (let day = 1; day <= totalDays; day++) {
            const d = new Date(Date.UTC(pickerMonthStart.getUTCFullYear(), pickerMonthStart.getUTCMonth(), day));
            const iso = toIsoDateUTC(d);

            const cell = document.createElement('button');
            cell.type = 'button';
            cell.className = 'pickerDay';
            cell.textContent = String(day);

            const start = pickerStartIso;
            const end = pickerEndIso || pickerStartIso;
            if (start && iso === start) {
                cell.classList.add('selected');
            }
            if (pickerEndIso && iso === pickerEndIso) {
                cell.classList.add('selected');
            }

            if (pickerStartIso && (pickerEndIso || pickerStartIso)) {
                if (isBetweenInclusive(iso, pickerStartIso, pickerEndIso || pickerStartIso)) {
                    cell.classList.add('inRange');
                }
            }

            cell.addEventListener('click', () => {
                // Click start then end. Clicking again resets.
                if (!pickerStartIso || (pickerStartIso && pickerEndIso)) {
                    pickerStartIso = iso;
                    pickerEndIso = '';
                } else {
                    // second click sets end
                    if (iso < pickerStartIso) {
                        pickerEndIso = pickerStartIso;
                        pickerStartIso = iso;
                    } else {
                        pickerEndIso = iso;
                    }
                }

                setPickerHelp();
                renderPicker();
            });

            grid.appendChild(cell);
        }
    }

    function openPickerFromInputs() {
        const date = String(document.getElementById('i_date').value || '');
        const from = String(document.getElementById('i_from').value || '');
        const to = String(document.getElementById('i_to').value || '');

        if (date) {
            pickerStartIso = date;
            pickerEndIso = date;
        } else if (from && to) {
            pickerStartIso = from;
            pickerEndIso = to;
        } else {
            pickerStartIso = '';
            pickerEndIso = '';
        }

        const base = parseIsoDateToUTC(pickerStartIso || from || date || to) || new Date();
        pickerMonthStart = startOfMonthUTC(base);
        renderPicker();
    }

    function applyPickerToInputs() {
        if (!pickerStartIso) {
            return;
        }

        const start = pickerStartIso;
        const end = pickerEndIso || pickerStartIso;

        if (start === end) {
            // single night
            document.getElementById('i_date').value = start;
            document.getElementById('i_from').value = '';
            document.getElementById('i_to').value = '';
        } else {
            document.getElementById('i_date').value = '';
            document.getElementById('i_from').value = start;
            document.getElementById('i_to').value = end;
        }

        updateInventorySelectedLabel();
    }

    function monthStartEnd(monthValue) {
        // monthValue: YYYY-MM
        const [y, m] = String(monthValue || '').split('-').map((x) => Number(x));
        if (!y || !m) return null;

        const start = new Date(Date.UTC(y, m - 1, 1));
        const end = new Date(Date.UTC(y, m, 1));

        return { start, end };
    }

    function daysInMonthUTC(start) {
        const y = start.getUTCFullYear();
        const m = start.getUTCMonth();
        const end = new Date(Date.UTC(y, m + 1, 1));
        return Math.round((end - start) / (24 * 60 * 60 * 1000));
    }

    function renderCalendar({ monthLabel, subLabel, startDate, nightsByDate }) {
        document.getElementById('calTitle').textContent = monthLabel;
        document.getElementById('calSub').textContent = subLabel;

        const grid = document.getElementById('calGrid');

        // Remove previous day cells (keep first 7 DOW cells)
        while (grid.children.length > 7) {
            grid.removeChild(grid.lastChild);
        }

        const firstDow = startDate.getUTCDay(); // 0 Sun
        const totalDays = daysInMonthUTC(startDate);

        for (let i = 0; i < firstDow; i++) {
            const pad = document.createElement('div');
            pad.className = 'day';
            pad.style.background = '#fafafa';
            pad.style.color = 'transparent';
            pad.textContent = '.';
            grid.appendChild(pad);
        }

        for (let day = 1; day <= totalDays; day++) {
            const d = new Date(Date.UTC(startDate.getUTCFullYear(), startDate.getUTCMonth(), day));
            const dateIso = toIsoDateUTC(d);
            const n = nightsByDate.get(dateIso) || null;

            const total = Number(n?.total ?? 0);
            const held = Number(n?.held ?? 0);
            const booked = Number(n?.booked ?? 0);
            const available = Number(n?.available ?? Math.max(0, total - held - booked));

            let badgeClass = 'badge missing';
            let badgeText = 'No inventory';

            if (n) {
                if (available >= 1) {
                    badgeClass = 'badge ok';
                    badgeText = `Avail ${available}`;
                } else {
                    badgeClass = 'badge err';
                    badgeText = 'Sold out';
                }
            }

            const cell = document.createElement('div');
            cell.className = 'day';

            const top = document.createElement('div');
            top.className = 'dayNum';

            const num = document.createElement('div');
            num.textContent = String(day);
            num.style.fontWeight = '750';

            const badge = document.createElement('div');
            badge.className = badgeClass;
            badge.textContent = badgeText;

            top.appendChild(num);
            top.appendChild(badge);

            const kv = document.createElement('div');
            kv.className = 'kv';
            kv.innerHTML = `
                <div class="k">Total</div><div class="mono" style="text-align:right">${total}</div>
                <div class="k">Held</div><div class="mono" style="text-align:right">${held}</div>
                <div class="k">Booked</div><div class="mono" style="text-align:right">${booked}</div>
                <div class="k">Avail</div><div class="mono" style="text-align:right">${available}</div>
            `;

            cell.appendChild(top);
            cell.appendChild(kv);
            grid.appendChild(cell);
        }
    }

    function setDefaultMonth() {
        const now = new Date();
        const y = now.getFullYear();
        const m = String(now.getMonth() + 1).padStart(2, '0');
        document.getElementById('a_month').value = `${y}-${m}`;
    }

    async function adminSearch() {
        const btn = document.getElementById('btnAdminSearch');
        const noticeEl = document.getElementById('adminNotice');
        const calEl = document.getElementById('calendar');

        hide(noticeEl);
        hide(calEl);

        const propertyId = Number(document.getElementById('a_property_id').value || 1);
        const roomType = String(document.getElementById('a_room_type_code').value || 'STD');
        const monthValue = String(document.getElementById('a_month').value || '');

        const range = monthStartEnd(monthValue);
        if (!range) {
            showNotice(noticeEl, 'err', 'Please pick a month.');
            return;
        }

        btn.disabled = true;
        const startedAt = Date.now();
        const minLoadingMs = 250;

        const checkIn = toIsoDateUTC(range.start);
        const checkOut = toIsoDateUTC(range.end);

        const url = new URL('/availability/api/v1/availability', window.location.origin);
        url.searchParams.set('property_id', String(propertyId));
        url.searchParams.set('room_type_code', roomType);
        url.searchParams.set('check_in', checkIn);
        url.searchParams.set('check_out', checkOut);

        let resp;
        try {
            resp = await fetch(url.toString(), { headers: { 'Accept': 'application/json' } });
        } catch (e) {
            const elapsed = Date.now() - startedAt;
            if (elapsed < minLoadingMs) await wait(minLoadingMs - elapsed);
            showNotice(noticeEl, 'err', friendlyServiceDown('Availability'));
            btn.disabled = false;
            return;
        }

        const body = await safeReadBody(resp);
        if (!resp.ok) {
            const elapsed = Date.now() - startedAt;
            if (elapsed < minLoadingMs) await wait(minLoadingMs - elapsed);
            showNotice(noticeEl, 'err', friendlyFromHttp('Availability', resp.status, body));
            btn.disabled = false;
            return;
        }

        const elapsed = Date.now() - startedAt;
        if (elapsed < minLoadingMs) await wait(minLoadingMs - elapsed);

        const data = (typeof body === 'object' && body) ? body : {};
        const nights = Array.isArray(data.nights) ? data.nights : [];

        const nightsByDate = new Map();
        for (const n of nights) {
            if (n && n.date) {
                nightsByDate.set(String(n.date), n);
            }
        }

        const monthLabel = `${monthValue} • Property ${propertyId} • ${roomType}`;
        const subLabel = `${checkIn} → ${checkOut}`;

        renderCalendar({
            monthLabel,
            subLabel,
            startDate: range.start,
            nightsByDate,
        });

        calEl.style.display = 'block';
        btn.disabled = false;
    }

    document.getElementById('btnAdminSearch').addEventListener('click', () => adminSearch());

    async function inventoryUpsert() {
        const btn = document.getElementById('btnInventoryUpsert');
        const noticeEl = document.getElementById('inventoryNotice');

        hide(noticeEl);

        const propertyId = Number(document.getElementById('i_property_id').value || 1);
        const roomType = String(document.getElementById('i_room_type_code').value || 'STD');
        const total = Number(document.getElementById('i_total').value || 0);

        const date = String(document.getElementById('i_date').value || '');
        const from = String(document.getElementById('i_from').value || '');
        const to = String(document.getElementById('i_to').value || '');

        if (!date && (!from || !to)) {
            showNotice(noticeEl, 'err', 'Pick a single Date, or a From+To range.');
            return;
        }

        const body = {
            property_id: propertyId,
            room_type_code: roomType,
            total,
        };

        if (date) {
            body.date = date;
        } else {
            body.from = from;
            body.to = to;
        }

        btn.disabled = true;

        let resp;
        try {
            resp = await fetch(new URL('/availability/api/v1/inventory', window.location.origin).toString(), {
                method: 'PUT',
                headers: { 'Accept': 'application/json', 'Content-Type': 'application/json' },
                body: JSON.stringify(body),
            });
        } catch (e) {
            showNotice(noticeEl, 'err', friendlyServiceDown('Availability'));
            btn.disabled = false;
            return;
        }

        const respBody = await safeReadBody(resp);
        if (!resp.ok) {
            showNotice(noticeEl, 'err', friendlyFromHttp('Availability', resp.status, respBody));
            btn.disabled = false;
            return;
        }

        const created = Number(respBody?.result?.created ?? 0);
        const updated = Number(respBody?.result?.updated ?? 0);
        const nights = Number(respBody?.result?.nights ?? 0);
        const appliedFrom = String(respBody?.from || '');
        const appliedTo = String(respBody?.to || '');
        showNotice(noticeEl, 'ok', `Saved inventory: ${nights} night(s) (${appliedFrom} → ${appliedTo}), total=${total} (created ${created}, updated ${updated}).`);
        btn.disabled = false;
    }

    async function bookingSearch() {
        const btn = document.getElementById('btnBookingSearch');
        const noticeEl = document.getElementById('bookingNotice');
        const resultsEl = document.getElementById('bookingResults');
        const rowsEl = document.getElementById('bookingRows');
        const nextEl = document.getElementById('bookingNext');

        hide(noticeEl);
        hide(resultsEl);
        rowsEl.innerHTML = '';
        nextEl.textContent = '';

        const propertyId = Number(document.getElementById('b_property_id').value || 1);
        const from = String(document.getElementById('b_from').value || '');
        const to = String(document.getElementById('b_to').value || '');
        const roomType = String(document.getElementById('b_room_type_code').value || '');
        const status = String(document.getElementById('b_status').value || '');
        const guestEmail = String(document.getElementById('b_guest_email').value || '');

        if (!from || !to) {
            showNotice(noticeEl, 'err', 'Please pick a date window.');
            return;
        }

        btn.disabled = true;

        const url = new URL('/bookings/api/v1/bookings', window.location.origin);
        url.searchParams.set('property_id', String(propertyId));
        url.searchParams.set('from', from);
        url.searchParams.set('to', to);
        if (roomType) url.searchParams.set('room_type_code', roomType);
        if (status) url.searchParams.set('status', status);
        if (guestEmail) url.searchParams.set('guest_email', guestEmail);

        let resp;
        try {
            resp = await fetch(url.toString(), { headers: { 'Accept': 'application/json' } });
        } catch (e) {
            showNotice(noticeEl, 'err', friendlyServiceDown('Bookings'));
            btn.disabled = false;
            return;
        }

        const body = await safeReadBody(resp);
        if (!resp.ok) {
            showNotice(noticeEl, 'err', friendlyFromHttp('Bookings', resp.status, body));
            btn.disabled = false;
            return;
        }

        const data = (typeof body === 'object' && body) ? body : {};
        const bookings = Array.isArray(data.bookings) ? data.bookings : [];

        for (const b of bookings) {
            const tr = document.createElement('tr');
            const id = String(b?.id ?? '');
            const statusText = String(b?.status ?? '');
            const pid = String(b?.property_id ?? '');
            const rtc = String(b?.room_type_code ?? '');
            const ci = String(b?.check_in ?? '');
            const co = String(b?.check_out ?? '');
            const ge = (b?.guest_email === null || typeof b?.guest_email === 'undefined') ? '' : String(b?.guest_email);

            tr.innerHTML = `
                <td class="mono"><a class="link" href="/bookings/api/v1/bookings/${encodeURIComponent(id)}" target="_blank" rel="noopener">${id}</a></td>
                <td>${statusText}</td>
                <td class="mono">${pid}</td>
                <td class="mono">${rtc}</td>
                <td class="mono">${ci} → ${co}</td>
                <td class="mono">${ge || '—'}</td>
            `;
            rowsEl.appendChild(tr);
        }

        resultsEl.style.display = 'block';

        const next = String(data.next_page_url || '');
        if (next) {
            nextEl.innerHTML = `More results: <a class="link" href="${next}" target="_blank" rel="noopener">next page</a>`;
        } else {
            nextEl.textContent = `Showing ${bookings.length} result(s).`;
        }

        btn.disabled = false;
    }

    document.getElementById('btnInventoryUpsert').addEventListener('click', () => inventoryUpsert());
    document.getElementById('btnBookingSearch').addEventListener('click', () => bookingSearch());

    setDefaultMonth();
    setDefaultDates();
    updateInventorySelectedLabel();

    document.getElementById('btnTogglePicker').addEventListener('click', () => {
        const el = document.getElementById('inventoryPicker');
        const isOpen = el.style.display !== 'none';
        el.style.display = isOpen ? 'none' : 'block';
        if (!isOpen) {
            openPickerFromInputs();
        }
    });

    document.getElementById('btnPickerPrev').addEventListener('click', () => {
        pickerMonthStart = addMonthsUTC(pickerMonthStart, -1);
        renderPicker();
    });

    document.getElementById('btnPickerNext').addEventListener('click', () => {
        pickerMonthStart = addMonthsUTC(pickerMonthStart, 1);
        renderPicker();
    });

    document.getElementById('btnPickerClear').addEventListener('click', () => {
        pickerStartIso = '';
        pickerEndIso = '';
        setPickerHelp();
        renderPicker();
    });

    document.getElementById('btnPickerApply').addEventListener('click', () => {
        applyPickerToInputs();
        document.getElementById('inventoryPicker').style.display = 'none';
    });
</script>
</body>
</html>
