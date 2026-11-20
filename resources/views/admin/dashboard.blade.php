@extends('admin.layouts.app')

@section('content')

    @php
        // Ikon SVG inline agar file ini berdiri sendiri (tanpa komponen/partial tambahan).
        $iconPaths = [
            'archive' => 'm20.25 7.5-.625 10.632a2.25 2.25 0 0 1-2.247 2.118H6.622a2.25 2.25 0 0 1-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125Z',
            'arrowright' => 'M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3',
            'arrowup' => 'M4.5 10.5 12 3m0 0 7.5 7.5M12 3v18',
            'bag' => 'M15.75 10.5V6a3.75 3.75 0 1 0-7.5 0v4.5m11.356-1.993 1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 0 1-1.12-1.243l1.264-12A1.125 1.125 0 0 1 5.513 7.5h12.974c.576 0 1.059.435 1.119 1.007ZM8.625 10.5a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm7.5 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z',
            'bell' => 'M14.857 17.082a23.848 23.848 0 0 0 5.454-1.31A8.967 8.967 0 0 1 18 9.75V9A6 6 0 0 0 6 9v.75a8.967 8.967 0 0 1-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 0 1-5.714 0m5.714 0a3 3 0 1 1-5.714 0',
            'cap' => 'M4.26 10.147a60.438 60.438 0 0 0-.491 6.347A48.62 48.62 0 0 1 12 20.904a48.62 48.62 0 0 1 8.232-4.41 60.46 60.46 0 0 0-.491-6.347m-15.482 0a50.636 50.636 0 0 0-2.658-.813A59.906 59.906 0 0 1 12 3.493a59.903 59.903 0 0 1 10.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.717 50.717 0 0 1 12 13.489a50.702 50.702 0 0 1 7.74-3.342M6.75 15a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5Zm0 0v-3.675A55.378 55.378 0 0 1 12 8.443m-7.007 11.55A5.981 5.981 0 0 0 6.75 15.75v-1.5',
            'chart' => 'M7.5 14.25v2.25m3-4.5v4.5m3-6.75v6.75m3-9v9M6 20.25h12A2.25 2.25 0 0 0 20.25 18V6A2.25 2.25 0 0 0 18 3.75H6A2.25 2.25 0 0 0 3.75 6v12A2.25 2.25 0 0 0 6 20.25Z',
            'check' => 'M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z',
            'dots' => 'M12 6.75a.75.75 0 1 1 0-1.5.75.75 0 0 1 0 1.5ZM12 12.75a.75.75 0 1 1 0-1.5.75.75 0 0 1 0 1.5ZM12 18.75a.75.75 0 1 1 0-1.5.75.75 0 0 1 0 1.5Z',
            'download' => 'M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3',
            'eye' => 'M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178ZM15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z',
            'pluscircle' => 'M12 9v6m3-3H9m12 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z',
            'trend' => 'M2.25 18 9 11.25l4.306 4.306a11.95 11.95 0 0 1 5.814-5.518l2.74-1.22m0 0-5.94-2.281m5.94 2.281-2.28 5.941',
            'wallet' => 'M21 12a2.25 2.25 0 0 0-2.25-2.25H15a3 3 0 1 1-6 0H5.25A2.25 2.25 0 0 0 3 12m18 0v6a2.25 2.25 0 0 1-2.25 2.25H5.25A2.25 2.25 0 0 1 3 18v-6m18 0V9M3 12V9m18 0a2.25 2.25 0 0 0-2.25-2.25H5.25A2.25 2.25 0 0 0 3 9m18 0V6a2.25 2.25 0 0 0-2.25-2.25H5.25A2.25 2.25 0 0 0 3 6v3',
            'wrench' => 'M11.42 15.17 17.25 21A2.652 2.652 0 0 0 21 17.25l-5.877-5.877M11.42 15.17l2.496-3.03c.317-.384.74-.626 1.208-.766M11.42 15.17l-4.655 5.653a2.548 2.548 0 1 1-3.586-3.586l6.837-5.63m5.108-.233c.55-.164 1.163-.188 1.743-.14a4.5 4.5 0 0 0 4.486-6.336l-3.276 3.277a3.004 3.004 0 0 1-2.25-2.25l3.276-3.276a4.5 4.5 0 0 0-6.336 4.486c.091 1.076-.071 2.264-.904 2.95l-.102.085m-1.745 1.437L5.909 7.5H4.5L2.25 3.75l1.5-1.5L7.5 4.5v1.409l4.26 4.26m-1.745 1.437 1.745-1.437m6.615 8.206L15.75 15.75M4.867 19.125h.008v.008h-.008v-.008Z',
        ];

        $icon = fn (string $name, string $class = 'h-5 w-5') => '<svg class="' . $class . '" fill="none" stroke="currentColor" stroke-width="1.7" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="' . ($iconPaths[$name] ?? '') . '" /></svg>';

        // Pembungkus aman: kalau query gagal (tabel/kolom berbeda), hasilnya null dan halaman tidak error.
        $safe = function (callable $fn, $default = null) {
            try {
                return $fn();
            } catch (\Throwable $e) {
                return $default;
            }
        };

        // Nilai payment_status yang dianggap sudah lunas. Sesuaikan dengan isi tabel orders.
        $paidStatuses = ['paid', 'settlement', 'capture', 'success', 'lunas'];

        // Variabel dari DashboardController dipakai bila ada; kalau tidak, dihitung di sini.
        $totalOrders = $totalOrders ?? $safe(fn () => \App\Models\Order::count(), 0);
        $totalProducts = $totalProducts ?? $safe(fn () => \App\Models\Product::count(), 0);
        $activeStudents = $activeStudents ?? $safe(fn () => \App\Models\User::where('role', 'siswa')->count(), 0);
        $totalRevenue = $totalRevenue ?? 0;
        if ($totalRevenue <= 0) {
            $totalRevenue = $safe(fn () => \App\Models\Order::whereIn('payment_status', $paidStatuses)->sum('total_amount'), 0);
        }

        // Kartu statistik memakai variabel dari DashboardController.
        // Bagian lain masih kosong; isi array di bawah dari controller bila datanya sudah dikirim.
        $stats = [
            ['label' => 'Total Pesanan', 'value' => number_format($totalOrders ?? 0, 0, ',', '.'), 'note' => 'Semua pesanan',
                'top' => 'border-t-[#25764C]', 'icon' => 'bag', 'iconBox' => 'bg-[#E3F3E9] text-[#25764C]'],
            ['label' => 'Total Produk', 'value' => number_format($totalProducts ?? 0, 0, ',', '.'), 'note' => 'Produk dan jasa',
                'top' => 'border-t-[#5B5BD6]', 'icon' => 'archive', 'iconBox' => 'bg-[#E9EAFB] text-[#5B5BD6]'],
            ['label' => 'Total Pendapatan', 'value' => 'Rp ' . number_format($totalRevenue ?? 0, 0, ',', '.'), 'note' => ($totalRevenue <= 0 && $totalOrders > 0) ? 'Belum ada pesanan lunas' : 'Dari transaksi',
                'top' => 'border-t-[#C2570C]', 'icon' => 'wallet', 'iconBox' => 'bg-[#FDEBDD] text-[#C2570C]'],
            ['label' => 'Total Siswa Aktif', 'value' => number_format($activeStudents ?? 0, 0, ',', '.'), 'note' => 'Siswa yang terdaftar',
                'top' => 'border-t-[#25764C]', 'icon' => 'cap', 'iconBox' => 'bg-[#E3F3E9] text-[#25764C]'],
        ];

        $months = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];

        // ---------------------------------------------------------------
        // Data grafik. Bisa dikirim dari controller (lebih rapi):
        //   $paidOrders      = koleksi pesanan lunas sejak awal tahun lalu (kolom: total_amount, created_at)
        //   $productionChart = [['label' => 'Apr', 'target' => 30, 'actual' => 25], ...]
        // Kalau tidak dikirim, dihitung di sini dari database.
        // ---------------------------------------------------------------
        $rp = fn ($v) => 'Rp ' . number_format($v, 0, ',', '.');
        $short = function ($v) {
            if ($v >= 1000000000) {
                return number_format($v / 1000000000, 2, ',', '.') . ' M';
            }
            if ($v >= 1000000) {
                return number_format($v / 1000000, 2, ',', '.') . ' Jt';
            }

            return number_format($v, 0, ',', '.');
        };

        // Penjualan: pesanan lunas (payment_status = paid) sejak awal tahun lalu.
        if (!isset($paidOrders)) {
            try {
                $paidOrders = \App\Models\Order::whereIn('payment_status', $paidStatuses)
                    ->where('created_at', '>=', now()->subYear()->startOfYear())
                    ->get(['total_amount', 'created_at']);
            } catch (\Throwable $e) {
                $paidOrders = collect();
            }
        }
        $paidOrders = collect($paidOrders)->filter(fn ($o) => $o->created_at);

        $byDay = $paidOrders->groupBy(fn ($o) => $o->created_at->format('Y-m-d'))
            ->map(fn ($g) => (float) $g->sum('total_amount'));
        $byMonth = $paidOrders->groupBy(fn ($o) => $o->created_at->format('Y-m'))
            ->map(fn ($g) => (float) $g->sum('total_amount'));
        $byYear = $paidOrders->groupBy(fn ($o) => $o->created_at->format('Y'))
            ->map(fn ($g) => (float) $g->sum('total_amount'));

        $monthNames = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
        $dayNames = ['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min'];

        // Minggu ini (Senin-Minggu) dan minggu lalu
        $weekStart = now()->startOfWeek();
        $weekVals = [];
        for ($i = 0; $i < 7; $i++) {
            $weekVals[] = (float) ($byDay[$weekStart->copy()->addDays($i)->format('Y-m-d')] ?? 0);
        }
        $prevWeekStart = $weekStart->copy()->subWeek();
        $prevWeek = 0;
        for ($i = 0; $i < 7; $i++) {
            $prevWeek += (float) ($byDay[$prevWeekStart->copy()->addDays($i)->format('Y-m-d')] ?? 0);
        }

        // Bulan ini (per hari)
        $monthVals = [];
        $monthLabels = [];
        $monthTips = [];
        for ($day = 1; $day <= now()->daysInMonth; $day++) {
            $monthVals[] = (float) ($byDay[now()->format('Y-m-') . str_pad($day, 2, '0', STR_PAD_LEFT)] ?? 0);
            $monthLabels[] = ($day === 1 || $day % 5 === 0) ? (string) $day : '';
            $monthTips[] = $day . ' ' . $months[now()->month - 1];
        }

        // Tahun ini (per bulan)
        $yearVals = [];
        for ($mo = 1; $mo <= 12; $mo++) {
            $yearVals[] = (float) ($byMonth[now()->year . '-' . str_pad($mo, 2, '0', STR_PAD_LEFT)] ?? 0);
        }

        $periods = [
            'week' => [
                'values' => $weekVals, 'labels' => $dayNames, 'tips' => $dayNames,
                'current' => now()->dayOfWeekIso - 1, 'dots' => true,
                'caption' => 'Minggu ini', 'vs' => 'vs minggu lalu',
                'total' => array_sum($weekVals), 'prev' => $prevWeek,
            ],
            'month' => [
                'values' => $monthVals, 'labels' => $monthLabels, 'tips' => $monthTips,
                'current' => now()->day - 1, 'dots' => false,
                'caption' => $monthNames[now()->month - 1] . ' ' . now()->year, 'vs' => 'vs bulan lalu',
                'total' => array_sum($monthVals),
                'prev' => (float) ($byMonth[now()->subMonthNoOverflow()->format('Y-m')] ?? 0),
            ],
            'year' => [
                'values' => $yearVals, 'labels' => $months, 'tips' => $months,
                'current' => now()->month - 1, 'dots' => true,
                'caption' => 'Tahun ' . now()->year, 'vs' => 'vs tahun lalu',
                'total' => array_sum($yearVals),
                'prev' => (float) ($byYear[now()->year - 1] ?? 0),
            ],
        ];

        // Geometri kurva tiap periode (y: 34 = tertinggi, 190 = nol)
        foreach ($periods as $key => $per) {
            $n = count($per['values']);
            $max = max($per['values']);

            $pts = [];
            foreach ($per['values'] as $i => $v) {
                $pts[] = [
                    round(($i + 0.5) * 520 / $n, 1),
                    $max > 0 ? round(190 - ($v / $max) * 156, 1) : 190,
                ];
            }

            $line = 'M' . $pts[0][0] . ',' . $pts[0][1];
            for ($i = 1; $i < $n; $i++) {
                $mx = round(($pts[$i - 1][0] + $pts[$i][0]) / 2, 2);
                $line .= ' C' . $mx . ',' . $pts[$i - 1][1] . ' ' . $mx . ',' . $pts[$i][1] . ' ' . $pts[$i][0] . ',' . $pts[$i][1];
            }

            $peak = $max > 0 ? array_search($max, $per['values']) : null;
            $tipText = '';
            $tipW = 0;
            $tipX = 0;
            if ($peak !== null) {
                $tipText = $per['tips'][$peak] . ': Rp ' . $short($per['values'][$peak]);
                $tipW = round(strlen($tipText) * 6 + 30);
                $tipX = min(max($pts[$peak][0] - $tipW / 2, 0), 520 - $tipW);
            }

            $periods[$key]['n'] = $n;
            $periods[$key]['max'] = $max;
            $periods[$key]['pts'] = $pts;
            $periods[$key]['line'] = $line;
            $periods[$key]['area'] = $line . ' L' . $pts[$n - 1][0] . ',200 L' . $pts[0][0] . ',200 Z';
            $periods[$key]['peak'] = $peak;
            $periods[$key]['tipText'] = $tipText;
            $periods[$key]['tipW'] = $tipW;
            $periods[$key]['tipX'] = $tipX;
            $periods[$key]['growth'] = $per['prev'] > 0 ? round(($per['total'] - $per['prev']) / $per['prev'] * 100, 1) : null;
        }

        // Produksi 6 bulan terakhir. Fallback: jumlah produksi yang dibuat per bulan.
        if (!isset($productionChart)) {
            $productionChart = [];
            try {
                if (class_exists(\App\Models\Production::class)) {
                    $counts = \App\Models\Production::where('created_at', '>=', now()->startOfMonth()->subMonths(5))
                        ->get(['created_at'])
                        ->groupBy(fn ($r) => $r->created_at->format('Y-m'))
                        ->map(fn ($g) => $g->count());

                    for ($i = 5; $i >= 0; $i--) {
                        $d = now()->startOfMonth()->subMonths($i);
                        $productionChart[] = [
                            'label' => $months[$d->month - 1],
                            'target' => 0,
                            'actual' => (int) ($counts[$d->format('Y-m')] ?? 0),
                        ];
                    }
                }
            } catch (\Throwable $e) {
                $productionChart = [];
            }
        }
        $prodMax = collect($productionChart)->flatMap(fn ($r) => [$r['target'], $r['actual']])->max() ?? 0;
        $targetSum = collect($productionChart)->sum('target');
        $actualSum = collect($productionChart)->sum('actual');

        // Contoh struktur item (kosongkan = tampil empty state):
        // $orders: ['id', 'name', 'org', 'product', 'date', 'status', 'total']
        // $payments: ['init', 'name', 'info', 'action']
        // $topProducts: ['name', 'info', 'badge', 'badgeCls']
        // $lines: ['name', 'major', 'pct' (angka 0-100), 'qty']
        // $reportNotes: ['dot', 'text'], $activities: ['dot', 'text', 'time']
        $statusMap = [
            'pending' => 'Menunggu', 'waiting' => 'Menunggu', 'menunggu' => 'Menunggu', 'unpaid' => 'Menunggu',
            'approved' => 'Diproses', 'confirmed' => 'Diproses', 'processing' => 'Diproses', 'production' => 'Diproses',
            'in_production' => 'Diproses', 'diproses' => 'Diproses',
            'completed' => 'Selesai', 'done' => 'Selesai', 'finished' => 'Selesai', 'delivered' => 'Selesai', 'selesai' => 'Selesai',
            'rejected' => 'Dibatalkan', 'cancelled' => 'Dibatalkan', 'canceled' => 'Dibatalkan', 'failed' => 'Dibatalkan', 'dibatalkan' => 'Dibatalkan',
        ];

        // 5 pesanan terbaru (relasi opsional: user, orderItems.product)
        $recentOrders = $safe(fn () => \App\Models\Order::with(['user', 'orderItems.product'])->latest()->take(5)->get())
            ?? $safe(fn () => \App\Models\Order::latest()->take(5)->get())
            ?? collect();

        $orders = $recentOrders->map(function ($o) use ($months, $statusMap) {
            $rawStatus = strtolower((string) data_get($o, 'status', ''));
            $items = data_get($o, 'orderItems');
            $first = data_get($o, 'orderItems.0.product.name');
            $extra = $items ? max($items->count() - 1, 0) : 0;
            $code = data_get($o, 'order_number') ?? data_get($o, 'invoice_number') ?? data_get($o, 'code');

            return [
                'id' => $code ? (string) $code : '#ORD-' . $o->getKey(),
                'name' => data_get($o, 'user.name') ?? data_get($o, 'customer_name') ?? '-',
                'org' => (string) (data_get($o, 'user.email') ?? ''),
                'product' => $first ? $first . ($extra > 0 ? ' +' . $extra . ' lainnya' : '') : '-',
                'date' => $o->created_at ? $o->created_at->format('d') . ' ' . $months[$o->created_at->month - 1] . ' ' . $o->created_at->year : '-',
                'status' => $statusMap[$rawStatus] ?? ($rawStatus !== '' ? ucfirst($rawStatus) : '-'),
                'total' => 'Rp ' . number_format((float) data_get($o, 'total_amount', 0), 0, ',', '.'),
            ];
        })->all();

        // Pembayaran yang masih pending
        $pendingPayments = $safe(fn () => \App\Models\Payment::with('order.user')->where('status', 'pending')->latest()->take(3)->get())
            ?? $safe(fn () => \App\Models\Payment::where('status', 'pending')->latest()->take(3)->get())
            ?? collect();
        $pendingCount = $safe(fn () => \App\Models\Payment::where('status', 'pending')->count(), 0);

        $payments = $pendingPayments->map(function ($pay) {
            $name = data_get($pay, 'order.user.name') ?? '-';

            return [
                'init' => strtoupper(mb_substr($name === '-' ? '?' : $name, 0, 2)),
                'name' => $name,
                'info' => '#ORD-' . data_get($pay, 'order_id') . ' • Rp ' . number_format((float) data_get($pay, 'amount', 0), 0, ',', '.'),
                'action' => 'Detail',
                'url' => route('admin.payments.show', $pay->getKey()),
            ];
        })->all();
        $topProducts = [];
        $lines = [];
        $reportNotes = [];
        $activities = [];

        $statusCls = [
            'Diproses' => 'bg-[#E2E8FB] text-[#3B4FC4]',
            'Selesai' => 'bg-[#CDEBD8] text-[#1F6B45]',
            'Menunggu' => 'bg-[#FDF0C4] text-[#92600A]',
            'Dibatalkan' => 'bg-[#FDE0E0] text-[#C0392B]',
        ];
    @endphp

    <div class="mx-auto flex max-w-[1240px] flex-col gap-6">

        {{-- Aksi cepat --}}
        <section class="flex flex-col gap-4 rounded-2xl border border-gray-100 bg-white p-6 shadow-sm sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="flex items-center gap-2 text-xl font-bold text-gray-900">
                    Aksi Cepat Operasional
                    <span class="h-2 w-2 rounded-full bg-[#25764C]"></span>
                </h1>
                <p class="mt-1 max-w-sm text-sm text-[#25764C]">
                    Kelola registrasi order, alur kerja siswa, dan logistik bengkel
                </p>
            </div>

            <div class="flex flex-wrap gap-3">
                <a href="{{ route('admin.orders.index') }}"
                    class="inline-flex items-center gap-2 rounded-xl bg-[#1F6B45] px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-[#185637]">
                    {!! $icon('pluscircle', 'h-4 w-4') !!}
                    Pesanan Baru
                </a>
                <a href="{{ route('admin.reports.export.excel') }}"
                    class="inline-flex items-center gap-2 rounded-xl bg-[#EAF0FB] px-4 py-2.5 text-sm font-semibold text-[#2F4B8A] transition hover:bg-[#DDE7F8]">
                    {!! $icon('download', 'h-4 w-4') !!}
                    Ekspor Laporan
                </a>
            </div>
        </section>

        {{-- Statistik --}}
        <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 xl:grid-cols-4">
            @foreach ($stats as $s)
                <div class="rounded-2xl border border-gray-100 border-t-4 {{ $s['top'] }} bg-white p-5 shadow-sm">
                    <div class="flex items-start justify-between">
                        <div class="flex h-12 w-12 items-center justify-center rounded-xl {{ $s['iconBox'] }}">
                            {!! $icon($s['icon'], 'h-6 w-6') !!}
                        </div>

                        <div class="flex items-center gap-1">
                            <span class="inline-flex items-center gap-1 rounded-full bg-gray-100 px-2 py-0.5 text-[11px] font-semibold text-gray-500">
                                {!! $icon('trend', 'h-3 w-3') !!}
                                0%
                            </span>
                            {!! $icon('dots', 'h-4 w-4 text-gray-400') !!}
                        </div>
                    </div>

                    <p class="mt-4 text-sm text-gray-500">{{ $s['label'] }}</p>
                    <p class="mt-1 text-2xl font-bold text-gray-900">{{ $s['value'] }}</p>
                    <p class="mt-3 text-sm text-[#25764C]">{{ $s['note'] }}</p>
                </div>
            @endforeach
        </div>

        {{-- Grafik --}}
        <div class="grid grid-cols-1 gap-6 xl:grid-cols-[1.4fr_1fr]">

            {{-- Grafik penjualan --}}
            <section class="rounded-2xl border border-gray-100 bg-white p-6 shadow-sm">
                <div class="flex flex-col gap-3 border-b border-gray-100 pb-4 sm:flex-row sm:items-start sm:justify-between">
                    <div>
                        <h2 class="text-lg font-semibold text-gray-900">Grafik Penjualan</h2>
                        <p class="mt-1 text-sm text-gray-500">Perkembangan penjualan Teaching Factory</p>
                    </div>

                    <div class="flex shrink-0 rounded-xl bg-[#EEF1F8] p-1 text-xs font-medium" role="tablist">
                        <button type="button" role="tab" data-sales-tab="week" aria-selected="false"
                            class="rounded-lg px-3 py-1.5 text-gray-500 transition">Minggu Ini</button>
                        <button type="button" role="tab" data-sales-tab="month" aria-selected="true"
                            class="rounded-lg bg-[#1F6B45] px-3 py-1.5 font-semibold text-white transition">Bulan Ini</button>
                        <button type="button" role="tab" data-sales-tab="year" aria-selected="false"
                            class="rounded-lg px-3 py-1.5 text-gray-500 transition">Tahun Ini</button>
                    </div>
                </div>

                @foreach ($periods as $key => $per)
                    <div data-sales-panel="{{ $key }}" class="{{ $key === 'month' ? '' : 'hidden' }}">

                        <div class="mt-5">
                            <p class="text-xs font-medium text-gray-400">{{ $per['caption'] }}</p>
                            <div class="mt-1 flex flex-wrap items-baseline gap-3">
                                <p class="text-3xl font-bold tracking-tight text-gray-900">{{ $rp($per['total']) }}</p>

                                @if ($per['growth'] !== null)
                                    <p class="inline-flex items-center gap-1 text-sm font-semibold {{ $per['growth'] >= 0 ? 'text-[#25764C]' : 'text-[#C0392B]' }}">
                                        {!! $icon('arrowup', 'h-3.5 w-3.5' . ($per['growth'] < 0 ? ' rotate-180' : '')) !!}
                                        {{ $per['growth'] >= 0 ? '+' : '' }}{{ number_format($per['growth'], 1, ',', '.') }}% {{ $per['vs'] }}
                                    </p>
                                @else
                                    <p class="text-sm text-gray-400">Belum ada data pembanding</p>
                                @endif
                            </div>
                        </div>

                        <div class="mt-4">
                            <div class="relative">
                                <svg viewBox="0 0 520 215" class="w-full" role="img" aria-label="Grafik penjualan {{ $per['caption'] }}">
                                    <defs>
                                        <linearGradient id="salesFill-{{ $key }}" x1="0" y1="0" x2="0" y2="1">
                                            <stop offset="0%" stop-color="#25764C" stop-opacity="0.22" />
                                            <stop offset="100%" stop-color="#25764C" stop-opacity="0" />
                                        </linearGradient>
                                    </defs>

                                    <g stroke="#E5E7EB" stroke-dasharray="3 4">
                                        <line x1="0" y1="45" x2="520" y2="45" />
                                        <line x1="0" y1="105" x2="520" y2="105" />
                                        <line x1="0" y1="165" x2="520" y2="165" />
                                    </g>

                                    @if ($per['max'] > 0)
                                        <path d="{{ $per['area'] }}" fill="url(#salesFill-{{ $key }})" />
                                        <path d="{{ $per['line'] }}" fill="none" stroke="#1F6B45" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" />

                                        @if ($per['dots'])
                                            @foreach ($per['pts'] as $i => $pt)
                                                @if ($per['values'][$i] > 0 && $i !== $per['peak'])
                                                    <circle cx="{{ $pt[0] }}" cy="{{ $pt[1] }}" r="3" fill="#fff" stroke="#1F6B45" stroke-width="1.5" />
                                                @endif
                                            @endforeach
                                        @endif

                                        <line x1="{{ $per['pts'][$per['peak']][0] }}" y1="{{ $per['pts'][$per['peak']][1] }}" x2="{{ $per['pts'][$per['peak']][0] }}" y2="200" stroke="#1F2937" stroke-dasharray="3 3" />
                                        <circle cx="{{ $per['pts'][$per['peak']][0] }}" cy="{{ $per['pts'][$per['peak']][1] }}" r="4.5" fill="#fff" stroke="#1F6B45" stroke-width="2" />

                                        <rect x="{{ $per['tipX'] }}" y="2" width="{{ $per['tipW'] }}" height="22" rx="6" fill="#1F2937" />
                                        <circle cx="{{ $per['tipX'] + 12 }}" cy="13" r="3" fill="#4ADE80" />
                                        <text x="{{ $per['tipX'] + 20 }}" y="16.5" font-size="10" font-weight="600" fill="#fff" font-family="Inter, sans-serif">{{ $per['tipText'] }}</text>
                                    @else
                                        <line x1="0" y1="190" x2="520" y2="190" stroke="#D1D5DB" stroke-width="1.5" />
                                    @endif
                                </svg>

                                @if ($per['max'] <= 0)
                                    <p class="absolute inset-0 flex items-center justify-center text-sm text-gray-400">Belum ada penjualan pada periode ini.</p>
                                @endif
                            </div>

                            <div class="grid text-center text-[11px] text-gray-500" style="grid-template-columns: repeat({{ $per['n'] }}, minmax(0, 1fr))">
                                @foreach ($per['labels'] as $i => $label)
                                    <span class="{{ $i === $per['current'] ? 'font-bold text-[#25764C]' : '' }}">{{ $label }}</span>
                                @endforeach
                            </div>
                        </div>

                    </div>
                @endforeach

                <script>
                    (function () {
                        var tabs = document.querySelectorAll('[data-sales-tab]');
                        var panels = document.querySelectorAll('[data-sales-panel]');
                        var onClasses = ['bg-[#1F6B45]', 'font-semibold', 'text-white'];

                        tabs.forEach(function (tab) {
                            tab.addEventListener('click', function () {
                                var key = tab.getAttribute('data-sales-tab');

                                tabs.forEach(function (t) {
                                    var on = t === tab;
                                    onClasses.forEach(function (c) { t.classList.toggle(c, on); });
                                    t.classList.toggle('text-gray-500', !on);
                                    t.setAttribute('aria-selected', on ? 'true' : 'false');
                                });

                                panels.forEach(function (panel) {
                                    panel.classList.toggle('hidden', panel.getAttribute('data-sales-panel') !== key);
                                });
                            });
                        });
                    })();
                </script>
            </section>

            {{-- Grafik produksi --}}
            <section class="rounded-2xl border border-gray-100 bg-white p-6 shadow-sm">
                <div class="flex items-start justify-between gap-3 border-b border-gray-100 pb-4">
                    <div>
                        <h2 class="text-lg font-semibold text-gray-900">Grafik Produksi</h2>
                        <p class="mt-1 text-sm text-gray-500">Jumlah produksi berdasarkan periode</p>
                    </div>

                    @if ($targetSum > 0)
                        <span class="inline-flex shrink-0 items-center gap-1 rounded-full bg-[#CDEBD8] px-2.5 py-1 text-xs font-semibold text-[#1F6B45]">
                            {!! $icon('check', 'h-3.5 w-3.5') !!}
                            {{ round($actualSum / $targetSum * 100) }}% Efisiensi
                        </span>
                    @endif
                </div>

                <div class="mt-4 flex items-center gap-5 text-xs font-medium text-gray-600">
                    @if ($targetSum > 0)
                        <span class="inline-flex items-center gap-1.5"><span class="h-2.5 w-2.5 rounded-sm bg-[#E4E8F7]"></span>Target Produksi</span>
                    @endif
                    <span class="inline-flex items-center gap-1.5"><span class="h-2.5 w-2.5 rounded-sm bg-[#1F6B45]"></span>Produksi Aktual</span>
                </div>

                @if ($prodMax > 0)
                    <div class="mt-6 grid h-52 items-end gap-2 border-b border-gray-100" style="grid-template-columns: repeat({{ count($productionChart) }}, minmax(0, 1fr))">
                        @foreach ($productionChart as $r)
                            <div class="flex h-full items-end justify-center gap-0.5">
                                @if ($targetSum > 0)
                                    <div class="w-4 rounded-t bg-[#E4E8F7]" style="height: {{ $r['target'] > 0 ? max(3, round($r['target'] / $prodMax * 100)) : 0 }}%" title="Target: {{ $r['target'] }}"></div>
                                @endif
                                <div class="w-4 rounded-t bg-[#1F6B45]" style="height: {{ $r['actual'] > 0 ? max(3, round($r['actual'] / $prodMax * 100)) : 0 }}%" title="Aktual: {{ $r['actual'] }}"></div>
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-2 grid text-center text-[11px] text-gray-500" style="grid-template-columns: repeat({{ count($productionChart) }}, minmax(0, 1fr))">
                        @foreach ($productionChart as $r)
                            <span class="{{ $loop->last ? 'font-bold text-[#25764C]' : '' }}">{{ $r['label'] }}</span>
                        @endforeach
                    </div>
                @else
                    <div class="mt-6 flex h-52 items-center justify-center rounded-xl border border-dashed border-gray-200 bg-gray-50 px-4 text-center">
                        <p class="text-sm text-gray-400">Belum ada data produksi.</p>
                    </div>
                @endif
            </section>

        </div>

        {{-- Pesanan, produksi, pembayaran, produk terlaris --}}
        <div class="grid grid-cols-1 items-start gap-6 xl:grid-cols-[2fr_1fr]">

            <div class="flex min-w-0 flex-col gap-6">

                {{-- Pesanan terbaru --}}
                <section class="overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-sm">
                    <div class="p-6 pb-4">
                        <h2 class="text-lg font-semibold text-gray-900">Pesanan Terbaru</h2>
                        <p class="mt-1 text-sm text-gray-500">Monitoring mutasi pesanan produksi barang dan jasa kejuruan</p>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full min-w-[640px] text-left text-[13px]">
                            <thead class="bg-[#F4F7FC] text-[11px] font-semibold uppercase tracking-wide text-gray-500">
                                <tr>
                                    <th class="px-5 py-3">ID Pesanan</th>
                                    <th class="px-3 py-3">Nama Pemesan</th>
                                    <th class="px-3 py-3">Produk / Jasa</th>
                                    <th class="px-3 py-3">Tanggal</th>
                                    <th class="px-3 py-3">Status</th>
                                    <th class="px-3 py-3">Total</th>
                                    <th class="px-5 py-3 text-right">Aksi</th>
                                </tr>
                            </thead>

                            <tbody class="divide-y divide-gray-100">
                                @forelse ($orders as $o)
                                    <tr class="align-middle">
                                        <td class="px-5 py-4 font-semibold text-[#25764C]">{{ $o['id'] }}</td>
                                        <td class="px-3 py-4">
                                            <p class="font-semibold text-gray-900">{{ $o['name'] }}</p>
                                            <p class="mt-0.5 text-[11px] text-gray-400">{{ $o['org'] }}</p>
                                        </td>
                                        <td class="max-w-[150px] px-3 py-4 text-gray-800">{{ $o['product'] }}</td>
                                        <td class="px-3 py-4 text-gray-500">{{ $o['date'] }}</td>
                                        <td class="px-3 py-4">
                                            <span class="inline-block rounded-full px-2.5 py-1 text-[11px] font-semibold {{ $statusCls[$o['status']] ?? 'bg-gray-100 text-gray-600' }}">
                                                {{ $o['status'] }}
                                            </span>
                                        </td>
                                        <td class="whitespace-nowrap px-3 py-4 font-semibold text-gray-900">{{ $o['total'] }}</td>
                                        <td class="px-5 py-4 text-right">
                                            <a href="{{ route('admin.orders.index') }}" class="inline-block text-gray-400 hover:text-[#25764C]" aria-label="Lihat pesanan">
                                                {!! $icon('eye', 'h-5 w-5') !!}
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="px-5 py-14 text-center text-sm text-gray-400">
                                            Belum ada pesanan.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="flex items-center justify-between gap-3 border-t border-[#DCE4F3] bg-[#F4F7FC] px-6 py-4 text-sm">
                        <span class="text-gray-500">Menampilkan {{ count($orders) }} dari {{ number_format($totalOrders, 0, ',', '.') }} pesanan</span>
                        <a href="{{ route('admin.orders.index') }}" class="inline-flex items-center gap-1 font-semibold text-[#25764C] hover:underline">
                            Lihat Semua Pesanan
                            {!! $icon('arrowright', 'h-4 w-4') !!}
                        </a>
                    </div>
                </section>

                {{-- Produksi berjalan --}}
                <section class="rounded-2xl border border-gray-100 bg-white p-6 shadow-sm">
                    <div class="flex items-start justify-between gap-3 border-b border-gray-100 pb-4">
                        <div>
                            <h2 class="text-lg font-semibold text-gray-900">Produksi Berjalan</h2>
                            <p class="mt-1 text-sm text-gray-500">Progress pengerjaan unit teaching factory per jurusan</p>
                        </div>
                        <span class="shrink-0 rounded-md bg-[#EAF0FB] px-2.5 py-1 text-xs font-medium text-[#2F4B8A]">{{ count($lines) }} Lini Aktif</span>
                    </div>

                    @forelse ($lines as $l)
                        @if ($loop->first)
                            <div class="mt-5 space-y-5">
                        @endif

                        <div>
                            <div class="flex items-baseline justify-between gap-3 text-sm">
                                <p class="font-semibold text-gray-900">
                                    {{ $l['name'] }}
                                    <span class="font-normal text-gray-500">({{ $l['major'] }})</span>
                                </p>
                                <p class="shrink-0 font-semibold text-[#25764C]">
                                    {{ $l['pct'] }}%
                                    <span class="font-normal text-gray-500">({{ $l['qty'] }})</span>
                                </p>
                            </div>
                            <div class="mt-2 h-2 rounded-full bg-[#E4EAF5]">
                                <div class="h-2 rounded-full bg-[#1F6B45]" style="width: {{ (int) $l['pct'] }}%"></div>
                            </div>
                        </div>

                        @if ($loop->last)
                            </div>
                        @endif
                    @empty
                        <div class="mt-5 flex h-32 items-center justify-center rounded-xl border border-dashed border-gray-200 bg-gray-50 px-4 text-center">
                            <p class="text-sm text-gray-400">Belum ada produksi yang berjalan.</p>
                        </div>
                    @endforelse
                </section>

            </div>

            <div class="flex min-w-0 flex-col gap-6">

                {{-- Pembayaran pending --}}
                <section class="rounded-2xl border border-gray-100 bg-white p-6 shadow-sm">
                    <div class="flex items-start justify-between gap-3 border-b border-gray-100 pb-4">
                        <div>
                            <h2 class="text-lg font-semibold text-gray-900">Pembayaran Pending</h2>
                            <p class="mt-1 text-sm text-gray-500">Perlu konfirmasi bendahara TeFa</p>
                        </div>
                    </div>

                    @forelse ($payments as $p)
                        @if ($loop->first)
                            <div class="mt-4 space-y-3">
                        @endif

                        <div class="flex items-center gap-3 rounded-xl bg-[#EEF3FB] p-3">
                            <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-[#C5E9D3] text-sm font-bold text-[#1F6B45]">
                                {{ $p['init'] }}
                            </span>
                            <div class="min-w-0 flex-1">
                                <p class="truncate text-sm font-semibold text-gray-900">{{ $p['name'] }}</p>
                                <p class="text-[11px] leading-snug text-gray-500">{{ $p['info'] }}</p>
                            </div>
                            <a href="{{ $p['url'] ?? route('admin.payments.index') }}"
                                class="shrink-0 rounded-md bg-[#1F6B45] px-2 py-1 text-[11px] font-semibold text-white">
                                {{ $p['action'] }}
                            </a>
                        </div>

                        @if ($loop->last)
                            </div>
                        @endif
                    @empty
                        <div class="mt-4 flex h-32 items-center justify-center rounded-xl border border-dashed border-gray-200 bg-gray-50 px-4 text-center">
                            <p class="text-sm text-gray-400">Belum ada pembayaran pending.</p>
                        </div>
                    @endforelse

                    <a href="{{ route('admin.payments.index') }}"
                        class="mt-4 flex items-center justify-center gap-1.5 rounded-xl bg-[#EAF0FB] px-4 py-3 text-sm font-semibold text-[#2F4B8A] transition hover:bg-[#DDE7F8]">
                        Lihat Semua Pembayaran
                        @if ($pendingCount > 0)
                            ({{ $pendingCount }})
                        @endif
                        {!! $icon('arrowright', 'h-4 w-4') !!}
                    </a>
                </section>

                {{-- Produk terlaris --}}
                <section class="rounded-2xl border border-gray-100 bg-white p-6 shadow-sm">
                    <div class="border-b border-gray-100 pb-4">
                        <h2 class="text-lg font-semibold text-gray-900">Produk Terlaris</h2>
                        <p class="mt-1 text-sm text-gray-500">Top performa penjualan produk TeFa</p>
                    </div>

                    @forelse ($topProducts as $t)
                        @if ($loop->first)
                            <div class="mt-4 space-y-4">
                        @endif

                        <div class="flex items-center gap-3">
                            <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-[#EEF0FB] text-sm font-bold text-[#4B4BC4]">
                                #{{ $loop->iteration }}
                            </span>
                            <div class="min-w-0 flex-1">
                                <p class="text-sm font-semibold text-gray-900">{{ $t['name'] }}</p>
                                <p class="text-[11px] text-gray-500">{{ $t['info'] }}</p>
                            </div>
                            <span class="shrink-0 rounded-full px-2.5 py-1 text-[11px] font-semibold {{ $t['badgeCls'] ?? 'bg-[#CDEBD8] text-[#1F6B45]' }}">
                                {{ $t['badge'] }}
                            </span>
                        </div>

                        @if ($loop->last)
                            </div>
                        @endif
                    @empty
                        <div class="mt-4 flex h-32 items-center justify-center rounded-xl border border-dashed border-gray-200 bg-gray-50 px-4 text-center">
                            <p class="text-sm text-gray-400">Belum ada data penjualan produk.</p>
                        </div>
                    @endforelse
                </section>

            </div>

        </div>

        {{-- Laporan & notifikasi --}}
        <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">

            {{-- Laporan eksekutif --}}
            <section class="flex flex-col rounded-2xl border border-gray-100 bg-white p-6 shadow-sm">
                <div class="flex items-start gap-3 border-b border-gray-100 pb-4">
                    <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-[#EAF0FB] text-[#2F4B8A]">
                        {!! $icon('trend', 'h-5 w-5') !!}
                    </span>
                    <div>
                        <h2 class="text-lg font-semibold text-gray-900">Laporan Eksekutif TeFa</h2>
                        <p class="text-sm text-gray-500">Ringkasan analisis operasional dan rantai pasok</p>
                    </div>
                </div>

                <div class="mt-5 flex-1">
                    @forelse ($reportNotes as $n)
                        @if ($loop->first)
                            <ul class="space-y-4 text-sm leading-relaxed text-gray-800">
                        @endif

                        <li class="flex gap-3">
                            <span class="mt-2 h-2.5 w-2.5 shrink-0 rounded-full {{ $n['dot'] ?? 'bg-[#25764C]' }}"></span>
                            <span>{{ $n['text'] }}</span>
                        </li>

                        @if ($loop->last)
                            </ul>
                        @endif
                    @empty
                        <div class="flex h-40 items-center justify-center rounded-xl border border-dashed border-gray-200 bg-gray-50 px-4 text-center">
                            <p class="text-sm text-gray-400">Belum ada ringkasan laporan.</p>
                        </div>
                    @endforelse
                </div>

                <a href="{{ route('admin.reports.index') }}"
                    class="mt-6 flex items-center justify-center gap-2 rounded-xl bg-[#1F6B45] px-4 py-3 text-sm font-semibold text-white transition hover:bg-[#185637]">
                    {!! $icon('chart', 'h-4 w-4') !!}
                    Lihat Laporan Lengkap Analitik TeFa
                </a>
            </section>

            {{-- Notifikasi --}}
            <section class="flex flex-col rounded-2xl border border-gray-100 bg-white p-6 shadow-sm">
                <div class="flex items-start gap-3 border-b border-gray-100 pb-4">
                    <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-[#EAF0FB] text-[#2F4B8A]">
                        {!! $icon('bell', 'h-5 w-5') !!}
                    </span>
                    <div>
                        <h2 class="text-lg font-semibold text-gray-900">Notifikasi Sistem &amp; Aktivitas</h2>
                        <p class="text-sm text-gray-500">Log audit dan pemicu peristiwa waktu nyata</p>
                    </div>
                </div>

                <div class="mt-5 flex-1">
                    @forelse ($activities as $a)
                        @if ($loop->first)
                            <ul class="space-y-4 text-sm text-gray-800">
                        @endif

                        <li class="flex items-start gap-3">
                            <span class="mt-1.5 h-2.5 w-2.5 shrink-0 rounded-full {{ $a['dot'] ?? 'bg-gray-300' }}"></span>
                            <span class="flex-1">{{ $a['text'] }}</span>
                            <span class="shrink-0 text-xs text-gray-400">{{ $a['time'] }}</span>
                        </li>

                        @if ($loop->last)
                            </ul>
                        @endif
                    @empty
                        <div class="flex h-40 items-center justify-center rounded-xl border border-dashed border-gray-200 bg-gray-50 px-4 text-center">
                            <p class="text-sm text-gray-400">Belum ada aktivitas.</p>
                        </div>
                    @endforelse
                </div>

                <div class="mt-6 flex justify-end border-t border-gray-100 pt-4">
                    <a href="{{ route('admin.reports.index') }}" class="inline-flex items-center gap-1 text-sm font-semibold text-[#25764C] hover:underline">
                        Lihat Semua Log Aktivitas
                        {!! $icon('arrowright', 'h-4 w-4') !!}
                    </a>
                </div>
            </section>

        </div>

    </div>

@endsection