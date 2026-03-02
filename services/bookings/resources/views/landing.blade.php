<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Booking System (Local)</title>
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
            max-width: 980px;
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
            max-width: 980px;
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
                grid-template-columns: 1fr 1fr;
            }
        }

        .spacer {
            margin-top: 12px;
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

        .tableWrap {
            margin-top: 12px;
            border: 1px solid var(--border);
            border-radius: 12px;
            overflow: hidden;
            background: #fff;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 13px;
        }

        thead th {
            text-align: left;
            font-size: 12px;
            color: var(--muted);
            font-weight: 700;
            padding: 10px 12px;
            background: #f9fafb;
            border-bottom: 1px solid var(--border);
        }

        tbody td {
            padding: 10px 12px;
            border-bottom: 1px solid var(--border);
            vertical-align: top;
        }

        tbody tr:last-child td {
            border-bottom: none;
        }

        td.num {
            text-align: right;
            font-variant-numeric: tabular-nums;
        }

        .pill {
            display: inline-flex;
            align-items: center;
            height: 22px;
            padding: 0 8px;
            border-radius: 999px;
            border: 1px solid var(--border);
            background: #fff;
            font-size: 12px;
            color: var(--muted-2);
        }

        .pill.ok {
            border-color: #86efac;
            background: #f0fdf4;
        }

        .pill.err {
            border-color: #fecaca;
            background: #fef2f2;
        }


        .out {
            white-space: pre-wrap;
            word-break: break-word;
            overflow-x: auto;
            font-family: ui-monospace, SFMono-Regular, Menlo, Consolas, monospace;
            font-size: 12px;
            background: #f8fafc;
            color: #0f172a;
            padding: 12px;
            border-radius: 12px;
            margin-top: 12px;
            border: 1px solid #e2e8f0;
        }

        details.rawWrap {
            margin-top: 12px;
            border: 1px solid var(--border);
            border-radius: 12px;
            background: #fff;
            overflow: hidden;
        }

        details.rawWrap > summary {
            cursor: pointer;
            padding: 10px 12px;
            list-style: none;
            font-weight: 650;
            color: var(--muted-2);
            background: #f9fafb;
            border-bottom: 1px solid var(--border);
        }

        details.rawWrap > summary::-webkit-details-marker {
            display: none;
        }

        details.rawWrap[open] > summary {
            color: var(--text);
        }

        details.rawWrap .out {
            margin-top: 0;
            border: none;
            border-radius: 0;
        }

        .footerHint {
            margin-top: 14px;
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
                <div class="brandSub">Microservices lab UI</div>
            </div>

            <div style="display:flex; align-items:center; gap: 8px;">
                <a href="./" class="muted" style="text-decoration: none; padding: 6px 10px; border-radius: 999px; border: 1px solid var(--border); background: {{ $isAdmin ? '#fff' : '#f3f4f6' }}; color: {{ $isAdmin ? 'var(--muted-2)' : 'var(--text)' }};">User</a>
                <a href="./admin" class="muted" style="text-decoration: none; padding: 6px 10px; border-radius: 999px; border: 1px solid var(--border); background: {{ $isAdmin ? '#f3f4f6' : '#fff' }}; color: {{ $isAdmin ? 'var(--text)' : 'var(--muted-2)' }};">Admin</a>
            </div>
        </div>

        <span class="muted">Gateway: <code class="mono">http://localhost:8080</code></span>
    </nav>
</header>

<main>
    <div class="pageTitle">
        <h1>Hotel Booking – Simple UI</h1>
        <div class="muted">Two actions only: check availability, then book.</div>
    </div>

    <section class="card">
        <div class="cardHead">
            <h2>1) Check Availability</h2>
            <div class="help">Checks the inventory in Availability for the selected dates.</div>
        </div>

        <div class="row">
            <div>
                <label>Property ID</label>
                <input id="property_id" type="number" min="1" value="1" />
            </div>
            <div>
                <label>Room Type</label>
                <select id="room_type_code">
                    <option value="STD">STD (Standard)</option>
                    <option value="DLX">DLX (Deluxe)</option>
                    <option value="STE">STE (Suite)</option>
                </select>
            </div>
        </div>

        <div class="row spacer">
            <div style="grid-column: 1 / -1;">
                <input id="check_in" type="hidden" />
                <input id="check_out" type="hidden" />

                <div class="notice" style="margin-top:0; background:#fff;">
                    <div style="display:flex; justify-content:space-between; gap:10px; align-items:center; flex-wrap:wrap;">
                        <div>
                            <div style="font-weight:750; letter-spacing:-0.01em;">Selected stay dates</div>
                            <div class="muted mono" id="staySelected">—</div>
                        </div>
                        <button id="btnToggleStayPicker" class="btnGhost" type="button">Pick dates on calendar</button>
                    </div>
                    <div class="muted" style="margin-top:6px;">Click check-in then check-out. Clicking the same date makes a 1-night stay.</div>
                </div>

                <div id="stayPicker" class="picker" style="display:none;">
                    <div class="pickerHead">
                        <div>
                            <div class="pickerTitle" id="stayPickerTitle">Dates</div>
                            <div class="muted" id="stayPickerSub">Select check-in and check-out</div>
                        </div>
                        <div class="pickerBtns">
                            <button id="btnStayPrev" class="btnGhost" type="button">Prev</button>
                            <button id="btnStayNext" class="btnGhost" type="button">Next</button>
                        </div>
                    </div>

                    <div class="pickerGrid" id="stayPickerGrid">
                        <div class="pickerDow">Sun</div>
                        <div class="pickerDow">Mon</div>
                        <div class="pickerDow">Tue</div>
                        <div class="pickerDow">Wed</div>
                        <div class="pickerDow">Thu</div>
                        <div class="pickerDow">Fri</div>
                        <div class="pickerDow">Sat</div>
                    </div>

                    <div class="pickerFoot">
                        <div class="pickerHelp" id="stayPickerHelp">Check-in: — • Check-out: —</div>
                        <div style="display:flex; gap:8px; align-items:center;">
                            <button id="btnStayClear" class="btnGhost" type="button">Clear</button>
                            <button id="btnStayApply" type="button">Apply</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="actions">
            <button id="btnAvailability" type="button">Check availability</button>
        </div>

        <div id="availabilityNotice" class="notice" style="display:none"></div>
        <div id="availabilitySummary" class="notice" style="display:none"></div>

        <div id="availabilityTableWrap" class="tableWrap" style="display:none">
            <table aria-label="Availability by night">
                <thead>
                <tr>
                    <th style="width: 34%">Date</th>
                    <th style="width: 16%" class="num">Total</th>
                    <th style="width: 16%" class="num">Held</th>
                    <th style="width: 16%" class="num">Booked</th>
                    <th style="width: 18%" class="num">Available</th>
                </tr>
                </thead>
                <tbody id="availabilityTableBody"></tbody>
            </table>
        </div>

        <div id="availabilityAlternatives" class="notice" style="display:none"></div>
    </section>

    <section class="card">
        <div class="cardHead">
            <h2>2) Confirm Booking</h2>
            <div class="help">Email is optional. We don’t send real emails in this lab. If the email is valid, we’ll show a “confirmation would be sent” message.</div>
        </div>

        <div class="row">
            <div>
                <label>Email (optional)</label>
                <input id="guest_email" type="text" placeholder="guest@example.com" />
            </div>
        </div>

        <div class="actions">
            <button id="btnBook" type="button">Book now</button>
        </div>

        <div id="bookingNotice" class="notice" style="display:none"></div>
        <details id="bookingDetailsWrap" class="rawWrap" style="display:none">
            <summary>Show response details</summary>
            <pre id="bookingDetails" class="out"></pre>
        </details>

        <div class="footerHint">Tip: If you see “inventory_missing”, seed inventory in Availability first.</div>
    </section>
</main>

<script>
    function showNotice(el, kind, message) {
        el.className = `notice ${kind}`;
        el.textContent = message;
        el.style.display = 'block';
    }

    function showOut(el, value) {
        el.textContent = value;
        el.style.display = 'block';
    }

    function showDetails(value) {
        const wrap = document.getElementById('bookingDetailsWrap');
        const pre = document.getElementById('bookingDetails');
        pre.textContent = value;
        wrap.open = false;
        wrap.style.display = 'block';
    }

    function fmtDate(d) {
        try {
            return new Date(d + 'T00:00:00Z').toISOString().slice(0, 10);
        } catch {
            return String(d);
        }
    }

    function addDays(dateIso, days) {
        const d = new Date(dateIso + 'T00:00:00Z');
        d.setUTCDate(d.getUTCDate() + days);
        return d.toISOString().slice(0, 10);
    }

    function wait(ms) {
        return new Promise((resolve) => setTimeout(resolve, ms));
    }

    function computeMinAvailable(nights) {
        if (!Array.isArray(nights) || nights.length === 0) return null;
        return nights.reduce((min, n) => Math.min(min, Number(n?.available ?? 0)), Number.POSITIVE_INFINITY);
    }

    function renderAvailabilityTable(nights) {
        const tbody = document.getElementById('availabilityTableBody');
        tbody.innerHTML = '';

        for (const n of nights) {
            const tr = document.createElement('tr');
            const date = String(n?.date ?? '');

            const tdDate = document.createElement('td');
            tdDate.textContent = date;

            const makeNum = (v) => {
                const td = document.createElement('td');
                td.className = 'num';
                td.textContent = String(Number(v ?? 0));
                return td;
            };

            tr.appendChild(tdDate);
            tr.appendChild(makeNum(n?.total));
            tr.appendChild(makeNum(n?.held));
            tr.appendChild(makeNum(n?.booked));
            tr.appendChild(makeNum(n?.available));

            tbody.appendChild(tr);
        }
    }

    function setDefaults() {
        const today = new Date();
        const checkIn = new Date(today);
        checkIn.setDate(checkIn.getDate() + 2);
        const checkOut = new Date(today);
        checkOut.setDate(checkOut.getDate() + 4);

        const toISO = (d) => d.toISOString().slice(0, 10);
        document.getElementById('check_in').value = toISO(checkIn);
        document.getElementById('check_out').value = toISO(checkOut);
    }

    // ---- Stay date picker (single calendar) ----
    let stayPickerMonthStart = null; // Date UTC @ month start
    let stayStartIso = '';
    let stayEndIso = '';

    function toIsoDateUTC(d) {
        return d.toISOString().slice(0, 10);
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

    function addDaysIso(dateIso, days) {
        const d = new Date(dateIso + 'T00:00:00Z');
        d.setUTCDate(d.getUTCDate() + days);
        return toIsoDateUTC(d);
    }

    function formatMonthLabelUTC(d) {
        try {
            return new Intl.DateTimeFormat(undefined, { month: 'long', year: 'numeric', timeZone: 'UTC' }).format(d);
        } catch {
            const y = d.getUTCFullYear();
            const m = String(d.getUTCMonth() + 1).padStart(2, '0');
            return `${y}-${m}`;
        }
    }

    function computedCheckoutIso() {
        if (!stayStartIso) return '';
        if (!stayEndIso) return addDaysIso(stayStartIso, 1);
        if (stayEndIso === stayStartIso) return addDaysIso(stayStartIso, 1);
        return stayEndIso;
    }

    function updateStaySelectedLabel() {
        const labelEl = document.getElementById('staySelected');
        const ci = String(document.getElementById('check_in').value || '');
        const co = String(document.getElementById('check_out').value || '');
        if (ci && co) {
            const a = new Date(ci + 'T00:00:00Z');
            const b = new Date(co + 'T00:00:00Z');
            const nights = Math.max(0, Math.round((b - a) / (24 * 60 * 60 * 1000)));
            labelEl.textContent = `${ci} → ${co} (${nights} night(s))`;
            return;
        }
        labelEl.textContent = '—';
    }

    function setStayPickerHelp() {
        const checkout = computedCheckoutIso();
        document.getElementById('stayPickerHelp').textContent = `Check-in: ${stayStartIso || '—'} • Check-out: ${checkout || '—'}`;
    }

    function isBetweenInclusive(iso, startIso, endIso) {
        if (!iso || !startIso || !endIso) return false;
        return iso >= startIso && iso <= endIso;
    }

    function renderStayPicker() {
        document.getElementById('stayPickerTitle').textContent = `Pick stay • ${formatMonthLabelUTC(stayPickerMonthStart)}`;
        setStayPickerHelp();

        const grid = document.getElementById('stayPickerGrid');
        while (grid.children.length > 7) {
            grid.removeChild(grid.lastChild);
        }

        const firstDow = stayPickerMonthStart.getUTCDay();
        const totalDays = daysInMonthUTCByMonthStart(stayPickerMonthStart);

        for (let i = 0; i < firstDow; i++) {
            const pad = document.createElement('div');
            pad.className = 'pickerDay muted';
            pad.textContent = '';
            pad.style.cursor = 'default';
            grid.appendChild(pad);
        }

        const displayEndIso = stayEndIso || stayStartIso;

        for (let day = 1; day <= totalDays; day++) {
            const d = new Date(Date.UTC(stayPickerMonthStart.getUTCFullYear(), stayPickerMonthStart.getUTCMonth(), day));
            const iso = toIsoDateUTC(d);

            const cell = document.createElement('button');
            cell.type = 'button';
            cell.className = 'pickerDay';
            cell.textContent = String(day);

            if (stayStartIso && iso === stayStartIso) cell.classList.add('selected');
            if (stayEndIso && iso === stayEndIso) cell.classList.add('selected');

            if (stayStartIso && displayEndIso) {
                if (isBetweenInclusive(iso, stayStartIso, displayEndIso)) {
                    cell.classList.add('inRange');
                }
            }

            cell.addEventListener('click', () => {
                if (!stayStartIso || (stayStartIso && stayEndIso)) {
                    stayStartIso = iso;
                    stayEndIso = '';
                } else {
                    if (iso < stayStartIso) {
                        stayEndIso = stayStartIso;
                        stayStartIso = iso;
                    } else {
                        stayEndIso = iso;
                    }
                }
                renderStayPicker();
            });

            grid.appendChild(cell);
        }
    }

    function openStayPickerFromInputs() {
        const ci = String(document.getElementById('check_in').value || '');
        const co = String(document.getElementById('check_out').value || '');

        stayStartIso = ci;
        // If inputs represent a 1-night stay, treat as single-date selection.
        if (ci && co && co === addDaysIso(ci, 1)) {
            stayEndIso = '';
        } else {
            stayEndIso = co;
        }

        const base = ci ? new Date(ci + 'T00:00:00Z') : (co ? new Date(co + 'T00:00:00Z') : new Date());
        stayPickerMonthStart = startOfMonthUTC(base);
        renderStayPicker();
    }

    function applyStayPickerToInputs() {
        if (!stayStartIso) return;

        document.getElementById('check_in').value = stayStartIso;
        document.getElementById('check_out').value = computedCheckoutIso();
        updateStaySelectedLabel();
    }

    function selectedParams() {
        return {
            property_id: Number(document.getElementById('property_id').value || 1),
            room_type_code: String(document.getElementById('room_type_code').value || 'STD'),
            check_in: String(document.getElementById('check_in').value || ''),
            check_out: String(document.getElementById('check_out').value || ''),
            guest_email: String(document.getElementById('guest_email').value || ''),
        };
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
        if (status === 409) {
            // Typical when another user grabs the last available unit.
            const detailsErr = body?.details?.error;
            if (detailsErr === 'not_available') {
                return 'Not available: someone else just booked the last room for these dates.';
            }
            if (detailsErr === 'inventory_missing') {
                return 'Inventory is missing for these dates. Seed/set inventory in Availability first.';
            }
            return 'Not available for these dates.';
        }
        if (status === 422) {
            return 'Please enter valid dates (check-out must be after check-in).';
        }
        if (status === 404) {
            return 'Route not found (check the gateway paths).';
        }

        if (typeof body === 'object' && body && body.message) {
            return String(body.message);
        }
        return `Request failed (HTTP ${status}).`;
    }

    async function checkAvailability() {
        const btn = document.getElementById('btnAvailability');
        const noticeEl = document.getElementById('availabilityNotice');
        const summaryEl = document.getElementById('availabilitySummary');
        const tableWrapEl = document.getElementById('availabilityTableWrap');
        const altEl = document.getElementById('availabilityAlternatives');

        const startedAt = Date.now();
        const minLoadingMs = 250;

        btn.disabled = true;
        noticeEl.style.display = 'none';
        summaryEl.style.display = 'none';
        tableWrapEl.style.display = 'none';
        altEl.style.display = 'none';

        const p = selectedParams();
        const url = new URL('/availability/api/v1/availability', window.location.origin);
        url.searchParams.set('property_id', String(p.property_id));
        url.searchParams.set('room_type_code', p.room_type_code);
        url.searchParams.set('check_in', p.check_in);
        url.searchParams.set('check_out', p.check_out);

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
        const minAvailable = computeMinAvailable(nights);
        const ok = nights.length > 0 && (minAvailable ?? 0) >= 1;

        if (nights.length === 0) {
            showNotice(noticeEl, 'err', 'No inventory returned. Seed inventory in Availability and try again.');
            btn.disabled = false;
            return;
        }

        showNotice(noticeEl, ok ? 'ok' : 'err', ok
            ? `Available: YES (min available across nights = ${minAvailable})`
            : `Available: NO (min available across nights = ${minAvailable})`
        );

        const nightsCount = nights.length;
        const range = `${String(data.check_in ?? p.check_in)} → ${String(data.check_out ?? p.check_out)}`;
        const pill = ok
            ? `<span class="pill ok">Available</span>`
            : `<span class="pill err">Not available</span>`;

        summaryEl.innerHTML = `${pill} <span class="muted" style="margin-left: 8px">${nightsCount} night(s) • ${range} • Min available: ${minAvailable}</span>`;
        summaryEl.style.display = 'block';

        renderAvailabilityTable(nights);
        tableWrapEl.style.display = 'block';

        if (!ok) {
            // Minimal, automatic suggestions: check other room types for the same dates,
            // and try a few forward date shifts for the same room type.
            altEl.style.display = 'block';
            altEl.textContent = 'Checking alternatives…';

            const otherTypes = ['STD', 'DLX', 'STE'].filter((t) => t !== p.room_type_code);
            const baseUrl = new URL('/availability/api/v1/availability', window.location.origin);

            const typeChecks = otherTypes.map(async (roomTypeCode) => {
                const u = new URL(baseUrl.toString());
                u.searchParams.set('property_id', String(p.property_id));
                u.searchParams.set('room_type_code', roomTypeCode);
                u.searchParams.set('check_in', p.check_in);
                u.searchParams.set('check_out', p.check_out);

                try {
                    const r = await fetch(u.toString(), { headers: { 'Accept': 'application/json' } });
                    const b = await safeReadBody(r);
                    if (!r.ok) return { roomTypeCode, ok: false, reason: `HTTP ${r.status}` };
                    const nights = Array.isArray(b?.nights) ? b.nights : [];
                    const min = computeMinAvailable(nights);
                    return { roomTypeCode, ok: (min ?? 0) >= 1, minAvailable: min ?? 0 };
                } catch {
                    return { roomTypeCode, ok: false, reason: 'network' };
                }
            });

            const windowLengthDays = Math.max(1, nightsCount);
            const dateShiftChecks = Array.from({ length: 7 }, (_, i) => i + 1).map(async (shift) => {
                const checkIn2 = addDays(p.check_in, shift);
                const checkOut2 = addDays(p.check_out, shift);

                const u = new URL(baseUrl.toString());
                u.searchParams.set('property_id', String(p.property_id));
                u.searchParams.set('room_type_code', p.room_type_code);
                u.searchParams.set('check_in', checkIn2);
                u.searchParams.set('check_out', checkOut2);

                try {
                    const r = await fetch(u.toString(), { headers: { 'Accept': 'application/json' } });
                    const b = await safeReadBody(r);
                    if (!r.ok) return { shift, ok: false };
                    const nights = Array.isArray(b?.nights) ? b.nights : [];
                    if (nights.length !== windowLengthDays) return { shift, ok: false };
                    const min = computeMinAvailable(nights);
                    return { shift, ok: (min ?? 0) >= 1, minAvailable: min ?? 0, checkIn: checkIn2, checkOut: checkOut2 };
                } catch {
                    return { shift, ok: false };
                }
            });

            const [typesRes, shiftsRes] = await Promise.all([
                Promise.allSettled(typeChecks),
                Promise.allSettled(dateShiftChecks),
            ]);

            const typeOk = typesRes
                .filter((r) => r.status === 'fulfilled')
                .map((r) => r.value)
                .filter((x) => x && typeof x === 'object')
                .sort((a, b) => (b.minAvailable ?? 0) - (a.minAvailable ?? 0));

            const bestShift = shiftsRes
                .filter((r) => r.status === 'fulfilled')
                .map((r) => r.value)
                .find((x) => x?.ok);

            const goodTypes = typeOk.filter((x) => x.ok);
            const lines = [];

            if (goodTypes.length > 0) {
                const top = goodTypes.slice(0, 2)
                    .map((x) => `${x.roomTypeCode} (min available ${x.minAvailable})`)
                    .join(' • ');
                lines.push(`Other room types for these dates: ${top}`);
            } else {
                lines.push('Other room types for these dates: none available (or not seeded).');
            }

            if (bestShift?.checkIn && bestShift?.checkOut) {
                lines.push(`Nearby dates for ${p.room_type_code}: try ${fmtDate(bestShift.checkIn)} → ${fmtDate(bestShift.checkOut)} (min available ${bestShift.minAvailable}).`);
            } else {
                lines.push(`Nearby dates for ${p.room_type_code}: try shifting dates forward a few days.`);
            }

            altEl.textContent = lines.join('\n');
        }

        btn.disabled = false;
    }

    async function createBooking() {
        const btn = document.getElementById('btnBook');
        const noticeEl = document.getElementById('bookingNotice');
        const detailsWrapEl = document.getElementById('bookingDetailsWrap');

        btn.disabled = true;
        noticeEl.style.display = 'none';
        detailsWrapEl.style.display = 'none';

        const p = selectedParams();
        const url = new URL('/bookings/api/v1/bookings', window.location.origin);

        const payload = {
            property_id: p.property_id,
            room_type_code: p.room_type_code,
            check_in: p.check_in,
            check_out: p.check_out,
        };

        const emailTrimmed = p.guest_email.trim();
        if (emailTrimmed !== '') {
            payload.guest_email = emailTrimmed;
        }

        let resp;
        try {
            resp = await fetch(url.toString(), {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                },
                body: JSON.stringify(payload),
            });
        } catch (e) {
            showNotice(noticeEl, 'err', friendlyServiceDown('Bookings'));
            btn.disabled = false;
            return;
        }

        const body = await safeReadBody(resp);
        if (!resp.ok) {
            showNotice(noticeEl, 'err', friendlyFromHttp('Bookings', resp.status, body));
            if (body) {
                showDetails(typeof body === 'string' ? body : JSON.stringify(body, null, 2));
            }
            btn.disabled = false;
            return;
        }

        const data = (typeof body === 'object' && body) ? body : {};
        const booking = data.booking || {};

        if (booking.guest_email) {
            showNotice(noticeEl, 'ok', `Booking confirmed. A confirmation would be sent to ${booking.guest_email} (demo).`);
        } else {
            showNotice(noticeEl, 'ok', 'Booking confirmed. (No valid email provided.)');
        }

        showDetails(JSON.stringify(data, null, 2));

        btn.disabled = false;
    }

    document.getElementById('btnAvailability').addEventListener('click', () => checkAvailability());
    document.getElementById('btnBook').addEventListener('click', () => createBooking());

    document.getElementById('btnToggleStayPicker').addEventListener('click', () => {
        const wrap = document.getElementById('stayPicker');
        const isOpen = wrap.style.display !== 'none';
        if (isOpen) {
            wrap.style.display = 'none';
            return;
        }
        wrap.style.display = 'block';
        openStayPickerFromInputs();
    });

    document.getElementById('btnStayPrev').addEventListener('click', () => {
        stayPickerMonthStart = addMonthsUTC(stayPickerMonthStart, -1);
        renderStayPicker();
    });

    document.getElementById('btnStayNext').addEventListener('click', () => {
        stayPickerMonthStart = addMonthsUTC(stayPickerMonthStart, 1);
        renderStayPicker();
    });

    document.getElementById('btnStayClear').addEventListener('click', () => {
        stayStartIso = '';
        stayEndIso = '';
        renderStayPicker();
    });

    document.getElementById('btnStayApply').addEventListener('click', () => {
        applyStayPickerToInputs();
        document.getElementById('stayPicker').style.display = 'none';
    });

    setDefaults();
    updateStaySelectedLabel();
</script>
</body>
</html>
