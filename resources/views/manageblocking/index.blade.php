@extends('layouts.app')

@section('title','Blocking Jadwal Dosen | Sistem Penjadwalan Kuliah')

@section('content')
@php
    $jamList = [];

    $start = strtotime('07:00');
    $end = strtotime('17:00');

    while ($start < $end) {
        $jamList[] = date('H:i', $start);
        $start = strtotime('+10 minutes', $start);
    }

    $hariList = ["Senin","Selasa","Rabu","Kamis","Jumat"];

    $blockingMap = [];

    foreach ($blocking as $b) {
        $hari = $b->hari;
        $jam = substr($b->jam_mulai, 0, 5);

        if (!isset($blockingMap[$hari])) {
            $blockingMap[$hari] = [];
        }

        if (!isset($blockingMap[$hari][$jam])) {
            $blockingMap[$hari][$jam] = [];
        }

        $blockingMap[$hari][$jam][] = [
            'kode_dosen' => $b->kode_dosen,
            'nama' => $b->dosen->nama_proper ?? $b->dosen->nama ?? 'Tanpa Nama',
        ];
    }
@endphp

<style>
    .blocking-toolbar-card .form-control,
    .blocking-toolbar-card .custom-select {
        height: 44px;
        border-radius: 10px;
    }

    .legend-item {
        display: inline-flex;
        align-items: center;
        margin-right: 18px;
        margin-bottom: 8px;
        font-size: 13px;
        font-weight: 600;
        color: #495057;
    }

    .legend-box {
        width: 16px;
        height: 16px;
        border-radius: 4px;
        margin-right: 8px;
        border: 1px solid rgba(0,0,0,.08);
    }

    .legend-available { background: #f8f9fa; }
    .legend-mine { background: #dc3545; }
    .legend-partial { background: #fff3cd; }
    .legend-full { background: #dee2e6; }

    .blocking-board-wrapper {
        overflow-x: auto;
    }

    .blocking-board {
        width: 100%;
        min-width: 920px;
        border-collapse: separate;
        border-spacing: 0;
        table-layout: fixed;
        background: #fff;
        border: 1px solid #dee2e6;
        border-radius: 12px;
        overflow: hidden;
    }

    .blocking-board thead th {
        position: sticky;
        top: 0;
        z-index: 5;
        background: #f8f9fa;
        color: #212529;
        text-align: center;
        font-weight: 700;
        border-bottom: 1px solid #dee2e6;
        border-right: 1px solid #dee2e6;
        padding: 12px 8px;
        vertical-align: middle;
    }

    .blocking-board thead th:last-child {
        border-right: none;
    }

    .blocking-board tbody td,
    .blocking-board tbody th {
        border-right: 1px solid #dee2e6;
        border-bottom: 1px solid #dee2e6;
        vertical-align: top;
    }

    .blocking-board tbody tr:last-child td,
    .blocking-board tbody tr:last-child th {
        border-bottom: none;
    }

    .blocking-board tbody td:last-child {
        border-right: none;
    }

    .time-col {
        width: 110px;
        min-width: 110px;
        background: #fbfbfc;
        text-align: center;
        padding: 0;
    }

    .time-label {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        min-height: 58px;
        padding: 8px;
    }

    .time-main {
        font-size: 18px;
        font-weight: 700;
        color: #343a40;
        line-height: 1;
    }

    .time-sub {
        font-size: 11px;
        color: #868e96;
        margin-top: 6px;
    }

    .day-head-main {
        display: block;
        font-size: 18px;
        font-weight: 800;
        line-height: 1.1;
        text-transform: uppercase;
    }

    .day-head-sub {
        display: block;
        font-size: 11px;
        color: #868e96;
        margin-top: 4px;
        font-style: italic;
        text-transform: uppercase;
    }

    .schedule-cell {
        padding: 6px;
        background: #ffffff;
    }

    .schedule-slot {
        min-height: 58px;
        border-radius: 10px;
        border: 1px solid #e9ecef;
        padding: 8px;
        cursor: pointer;
        transition: all .2s ease;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        user-select: none;
    }

    .schedule-slot:hover {
        transform: translateY(-1px);
        box-shadow: 0 6px 18px rgba(0,0,0,.08);
    }

    .schedule-slot.drag-preview-add {
        outline: 3px solid #2563eb;
        background: #dbeafe !important;
    }

    .schedule-slot.drag-preview-delete {
        outline: 3px solid #dc2626;
        background: #fee2e2 !important;
    }

    .slot-available {
        background: #ffffff;
    }

    .slot-available:hover {
        background: #f3f8ff;
        border-color: #8bb9ff;
    }

    .slot-mine {
        background: linear-gradient(135deg, #ff5f6d 0%, #d90429 100%);
        color: #fff;
        border-color: #d90429;
    }

    .slot-partial {
        background: #fff8e1;
        border-color: #ffe08a;
    }

    .slot-full {
        background: #eef1f4;
        border-color: #ced4da;
        cursor: not-allowed;
        opacity: .95;
    }

    .slot-topline {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 8px;
        margin-bottom: 4px;
    }

    .slot-badge {
        display: inline-flex;
        align-items: center;
        border-radius: 999px;
        padding: 3px 8px;
        font-size: 10px;
        font-weight: 700;
        letter-spacing: .2px;
    }

    .slot-available .slot-badge {
        background: #e9f2ff;
        color: #1d4ed8;
    }

    .slot-mine .slot-badge {
        background: rgba(255,255,255,.18);
        color: #fff;
        border: 1px solid rgba(255,255,255,.22);
    }

    .slot-partial .slot-badge {
        background: #fff3cd;
        color: #856404;
    }

    .slot-full .slot-badge {
        background: #dee2e6;
        color: #495057;
    }

    .slot-icon {
        font-size: 15px;
        opacity: .9;
    }

    .slot-title {
        font-size: 12px;
        font-weight: 700;
        margin-bottom: 3px;
        line-height: 1.25;
    }

    .slot-meta {
        font-size: 11px;
        line-height: 1.35;
        opacity: .95;
    }

    .slot-empty-text {
        font-size: 11px;
        color: #6c757d;
        line-height: 1.35;
    }

    .slot-hint {
        font-size: 10px;
        margin-top: 4px;
        opacity: .8;
    }

    .counter-box {
        border-radius: 12px;
        background: #f8f9fa;
        padding: 14px 16px;
        border: 1px solid #e9ecef;
        min-height: 78px;
    }

    .counter-label {
        font-size: 12px;
        color: #6c757d;
        text-transform: uppercase;
        font-weight: 700;
        letter-spacing: .4px;
    }

    .counter-value {
        font-size: 28px;
        font-weight: 800;
        line-height: 1;
        margin-top: 8px;
        color: #212529;
    }

    .page-note {
        font-size: 13px;
        color: #6c757d;
        margin-top: 8px;
    }

    @media (max-width: 768px) {
        .day-head-main {
            font-size: 16px;
        }

        .time-main {
            font-size: 16px;
        }
    }
</style>

<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Blocking Jadwal Dosen</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item">
                        <a href="/home/dashboard"><i class="fas fa-igloo mr-2"></i>Home</a>
                    </li>
                    <li class="breadcrumb-item active">Blocking Jadwal Dosen</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">

        @if(isset($belumDipasangkan) && $belumDipasangkan)
            <div class="alert alert-warning">
                <h5><i class="fas fa-exclamation-triangle mr-2"></i>Akun Belum Dipasangkan</h5>
                <p class="mb-0">Akun Anda belum dipasangkan ke data dosen oleh Admin. Silakan hubungi Admin untuk memasangkan akun Anda ke data dosen agar bisa menggunakan fitur pemblokiran jadwal.</p>
            </div>
        @else

        <div class="card blocking-toolbar-card">
            <div class="card-header bg-greenTheme">
                <h3 class="card-title text-whiteTheme">
                    <i class="fas fa-ban mr-2"></i>Pengaturan Ketersediaan Dosen
                </h3>
            </div>

            <div class="card-body">
                <div class="row">
                    <div class="col-lg-5 col-md-6">
                        <label for="kode_dosen">Pilih Dosen</label>
                        <select id="kode_dosen" class="custom-select" {{ count($dosen) == 1 ? 'disabled' : '' }}>
                            @foreach($dosen as $d)
                                <option value="{{ $d->kode_dosen }}">
                                    {{ $d->nama_proper ?? $d->nama }}
                                </option>
                            @endforeach
                        </select>
                        @if(isset($user_login) && $user_login->role_id == 2)
                            <div class="page-note text-info">
                                <i class="fas fa-info-circle mr-1"></i>Anda hanya dapat memblokir jadwal untuk nama dosen Anda sendiri.
                            </div>
                        @else
                            <div class="page-note">
                                Klik tahan lalu geser ke bawah atau ke samping untuk blocking banyak slot. Drag dari slot merah untuk menghapus banyak slot.
                            </div>
                        @endif
                    </div>

                    <div class="col-lg-7 col-md-6">
                        <div class="row">
                            <div class="col-md-6 mt-3 mt-md-0">
                                <div class="counter-box">
                                    <div class="counter-label">Dosen terpilih</div>
                                    <div class="counter-value" id="selected-lecturer-short">-</div>
                                </div>
                            </div>
                            <div class="col-md-6 mt-3 mt-md-0">
                                <div class="counter-box">
                                    <div class="counter-label">Total blok dosen ini / 30 slot</div>
                                    <div class="counter-value" id="selected-block-count">0/30</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <hr>

                <div class="mb-2">
                    <span class="legend-item">
                        <span class="legend-box legend-available"></span>Slot kosong
                    </span>
                    <span class="legend-item">
                        <span class="legend-box legend-mine"></span>Diblok dosen terpilih
                    </span>
                    <span class="legend-item">
                        <span class="legend-box legend-partial"></span>Sudah diblok dosen lain, masih bisa ikut blok
                    </span>
                    <span class="legend-item">
                        <span class="legend-box legend-full"></span>Penuh, tidak bisa dipilih
                    </span>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-body p-0">
                <div class="blocking-board-wrapper">
                    <table class="blocking-board">
                        <thead>
                            <tr>
                                <th style="width: 110px;">
                                    <span class="day-head-main">WAKTU</span>
                                    <span class="day-head-sub">TIME</span>
                                </th>

                                @foreach($hariList as $hari)
                                    <th>
                                        <span class="day-head-main">{{ strtoupper($hari) }}</span>
                                        <span class="day-head-sub">
                                            @if($hari === 'Senin') MONDAY
                                            @elseif($hari === 'Selasa') TUESDAY
                                            @elseif($hari === 'Rabu') WEDNESDAY
                                            @elseif($hari === 'Kamis') THURSDAY
                                            @else FRIDAY
                                            @endif
                                        </span>
                                    </th>
                                @endforeach
                            </tr>
                        </thead>

                        <tbody>
                            @foreach($jamList as $rowIndex => $jam)
                                @php
                                    $jamSelesai = date('H:i', strtotime($jam . ' +10 minutes'));
                                @endphp

                                <tr>
                                    <th class="time-col">
                                        <div class="time-label">
                                            <div class="time-main">{{ $jam }}</div>
                                            <div class="time-sub">{{ $jam }} - {{ $jamSelesai }}</div>
                                        </div>
                                    </th>

                                    @foreach($hariList as $colIndex => $hari)
                                        @php
                                            $slotData = $blockingMap[$hari][$jam] ?? [];
                                            $slotOwners = array_map(fn($x) => $x['kode_dosen'], $slotData);
                                            $slotNames = array_map(fn($x) => $x['nama'], $slotData);
                                        @endphp

                                        <td class="schedule-cell">
                                            <div
                                                class="schedule-slot"
                                                data-hari="{{ $hari }}"
                                                data-jam="{{ $jam }}"
                                                data-row="{{ $rowIndex }}"
                                                data-col="{{ $colIndex }}"
                                                data-owners='@json(array_values($slotOwners))'
                                                data-names='@json(array_values($slotNames))'
                                            ></div>
                                        </td>
                                    @endforeach
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        @endif

    </div>
</section>

<script>
    const csrfToken = '{{ csrf_token() }}';
    const dosenSelect = document.getElementById('kode_dosen');
    const blockCountEl = document.getElementById('selected-block-count');
    const lecturerShortEl = document.getElementById('selected-lecturer-short');

    let isDragging = false;
    let dragStarted = false;
    let dragStartSlot = null;
    let dragSlots = [];
    let dragMode = 'add';

    function getSelectedLecturer() {
        const option = dosenSelect.options[dosenSelect.selectedIndex];

        return {
            kode: option.value,
            nama: option.text.trim()
        };
    }

    function getShortName(name) {
        if (!name) return '-';

        const words = name.trim().split(/\s+/);
        return words.length > 2 ? words.slice(0, 2).join(' ') : name;
    }

    function getOwners(el) {
        try {
            return JSON.parse(el.dataset.owners || '[]');
        } catch (e) {
            return [];
        }
    }

    function getNames(el) {
        try {
            return JSON.parse(el.dataset.names || '[]');
        } catch (e) {
            return [];
        }
    }

    function setOwners(el, owners) {
        el.dataset.owners = JSON.stringify(owners);
    }

    function setNames(el, names) {
        el.dataset.names = JSON.stringify(names);
    }

    function escapeHtml(text) {
        const div = document.createElement('div');
        div.innerText = text;
        return div.innerHTML;
    }

    function renderSlot(el) {
        const selected = getSelectedLecturer();
        const owners = getOwners(el);
        const names = getNames(el);
        const isMine = owners.includes(selected.kode);
        const ownerCount = owners.length;

        el.classList.remove('slot-available', 'slot-mine', 'slot-partial', 'slot-full');

        let html = '';
        let titleAttr = '';

        if (ownerCount === 0) {
            el.classList.add('slot-available');

            html = `
                <div class="slot-topline">
                    <span class="slot-badge">TERSEDIA</span>
                    <i class="slot-icon far fa-calendar-plus"></i>
                </div>
                <div>
                    <div class="slot-title">Belum ada blocking</div>
                    <div class="slot-empty-text">Drag untuk memblokir.</div>
                    <div class="slot-hint">1 slot = 10 menit</div>
                </div>
            `;
        } else if (isMine) {
            el.classList.add('slot-mine');

            const otherNames = names.filter((_, index) => owners[index] !== selected.kode);

            let metaText = `Dosen: ${escapeHtml(selected.nama)}`;

            if (otherNames.length > 0) {
                metaText += `<br>Juga diblok oleh: ${escapeHtml(otherNames.join(', '))}`;
            }

            html = `
                <div class="slot-topline">
                    <span class="slot-badge">DIBLOK</span>
                    <i class="slot-icon fas fa-ban"></i>
                </div>
                <div>
                    <div class="slot-title">Dosen berhalangan</div>
                    <div class="slot-meta">${metaText}</div>
                    <div class="slot-hint">Drag dari sini untuk menghapus.</div>
                </div>
            `;
        } else if (ownerCount < 2) {
            el.classList.add('slot-partial');
            titleAttr = names.join(', ');

            html = `
                <div class="slot-topline">
                    <span class="slot-badge">TERPAKAI</span>
                    <i class="slot-icon fas fa-user-clock"></i>
                </div>
                <div>
                    <div class="slot-title">Diblok dosen lain</div>
                    <div class="slot-meta">${escapeHtml(names.join(', '))}</div>
                    <div class="slot-hint">Masih bisa ditambahkan.</div>
                </div>
            `;
        } else {
            el.classList.add('slot-full');
            titleAttr = names.join(', ');

            html = `
                <div class="slot-topline">
                    <span class="slot-badge">PENUH</span>
                    <i class="slot-icon fas fa-lock"></i>
                </div>
                <div>
                    <div class="slot-title">Slot sudah penuh</div>
                    <div class="slot-meta">${escapeHtml(names.join(', '))}</div>
                    <div class="slot-hint">Maksimal 2 dosen.</div>
                </div>
            `;
        }

        el.innerHTML = html;
        el.setAttribute('title', titleAttr || '');
    }

    function renderAllSlots() {
        document.querySelectorAll('.schedule-slot').forEach(renderSlot);
        updateSummary();
    }

    function updateSummary() {
        const selected = getSelectedLecturer();
        let myCount = 0;

        document.querySelectorAll('.schedule-slot').forEach(el => {
            const owners = getOwners(el);

            if (owners.includes(selected.kode)) {
                myCount++;
            }
        });

        blockCountEl.innerText = `${myCount}/30`;
        lecturerShortEl.innerText = getShortName(selected.nama);
    }

    function getCurrentCount() {
        const selected = getSelectedLecturer();

        return Array.from(document.querySelectorAll('.schedule-slot')).filter(slot => {
            return getOwners(slot).includes(selected.kode);
        }).length;
    }

    function updateLocalDataAfterAdd(el, kode, nama) {
        const owners = getOwners(el);
        const names = getNames(el);

        if (!owners.includes(kode)) {
            owners.push(kode);
            names.push(nama);
        }

        setOwners(el, owners);
        setNames(el, names);
    }

    function updateLocalDataAfterRemove(el, kode) {
        const owners = getOwners(el);
        const names = getNames(el);

        const nextOwners = [];
        const nextNames = [];

        owners.forEach((owner, index) => {
            if (owner !== kode) {
                nextOwners.push(owner);
                nextNames.push(names[index]);
            }
        });

        setOwners(el, nextOwners);
        setNames(el, nextNames);
    }

    function postJson(url, payload) {
        return fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify(payload)
        }).then(async response => {
            const data = await response.json().catch(() => ({}));

            if (!response.ok || data.error) {
                throw data;
            }

            return data;
        });
    }

    function clearDragPreview() {
        document.querySelectorAll('.schedule-slot.drag-preview-add, .schedule-slot.drag-preview-delete').forEach(slot => {
            slot.classList.remove('drag-preview-add', 'drag-preview-delete');
        });
    }

    function getSlotsInRectangle(startSlot, currentSlot) {
        const startRow = parseInt(startSlot.dataset.row);
        const startCol = parseInt(startSlot.dataset.col);
        const currentRow = parseInt(currentSlot.dataset.row);
        const currentCol = parseInt(currentSlot.dataset.col);

        const minRow = Math.min(startRow, currentRow);
        const maxRow = Math.max(startRow, currentRow);
        const minCol = Math.min(startCol, currentCol);
        const maxCol = Math.max(startCol, currentCol);

        return Array.from(document.querySelectorAll('.schedule-slot')).filter(slot => {
            const row = parseInt(slot.dataset.row);
            const col = parseInt(slot.dataset.col);

            return row >= minRow && row <= maxRow && col >= minCol && col <= maxCol;
        });
    }

    function previewDragRange(currentSlot) {
        if (!dragStartSlot || !currentSlot) return;

        clearDragPreview();

        dragSlots = getSlotsInRectangle(dragStartSlot, currentSlot);

        const selected = getSelectedLecturer();

        dragSlots.forEach(slot => {
            const owners = getOwners(slot);
            const isMine = owners.includes(selected.kode);

            if (dragMode === 'delete') {
                if (isMine) {
                    slot.classList.add('drag-preview-delete');
                }
            } else {
                if (!isMine && owners.length < 2) {
                    slot.classList.add('drag-preview-add');
                }
            }
        });
    }

    async function addSlot(el) {
        const selected = getSelectedLecturer();
        const owners = getOwners(el);

        if (owners.includes(selected.kode)) return false;
        if (owners.length >= 2) return false;

        if (getCurrentCount() >= 30) {
            alert('Maksimal blocking adalah 5 jam atau 30 slot per dosen.');
            return false;
        }

        await postJson('/blocking-jadwal', {
            kode_dosen: selected.kode,
            hari: el.dataset.hari,
            jam_mulai: el.dataset.jam
        });

        updateLocalDataAfterAdd(el, selected.kode, selected.nama);
        return true;
    }

    async function removeSlot(el) {
        const selected = getSelectedLecturer();
        const owners = getOwners(el);

        if (!owners.includes(selected.kode)) return false;

        await postJson('/blocking-jadwal/delete', {
            kode_dosen: selected.kode,
            hari: el.dataset.hari,
            jam_mulai: el.dataset.jam
        });

        updateLocalDataAfterRemove(el, selected.kode);
        return true;
    }

    async function saveDragSlots() {
        let maxAlertShown = false;

        for (const slot of dragSlots) {
            try {
                if (dragMode === 'delete') {
                    await removeSlot(slot);
                } else {
                    if (getCurrentCount() >= 30) {
                        if (!maxAlertShown) {
                            alert('Maksimal blocking adalah 5 jam atau 30 slot per dosen.');
                            maxAlertShown = true;
                        }
                        break;
                    }

                    await addSlot(slot);
                }
            } catch (err) {
                console.warn(err.error || 'Gagal memproses salah satu slot.');
            }
        }

        clearDragPreview();
        renderAllSlots();
    }

    document.querySelectorAll('.schedule-slot').forEach(el => {
        el.addEventListener('mousedown', function (e) {
            e.preventDefault();

            const selected = getSelectedLecturer();
            const owners = getOwners(this);

            isDragging = true;
            dragStarted = false;
            dragStartSlot = this;
            dragSlots = [this];

            dragMode = owners.includes(selected.kode) ? 'delete' : 'add';

            previewDragRange(this);
        });

        el.addEventListener('mouseenter', function () {
            if (!isDragging) return;

            dragStarted = true;
            previewDragRange(this);
        });
    });

    document.addEventListener('mouseup', function () {
        if (!isDragging) return;

        const targetSlot = dragStartSlot;

        isDragging = false;
        dragStartSlot = null;

        if (dragStarted && dragSlots.length > 0) {
            saveDragSlots();
        } else if (targetSlot) {
            dragSlots = [targetSlot];
            saveDragSlots();
        }

        dragStarted = false;
    });

    dosenSelect.addEventListener('change', renderAllSlots);

    document.addEventListener('DOMContentLoaded', renderAllSlots);
</script>
@endsection