<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Temukan Partner - TEMPA</title>
    <link rel="stylesheet" href="/css/findings.css">
</head>
<body>
    <div class="glow glow-hero-tr"></div>
    <div class="glow glow-hero-bl"></div>

    <header class="navbar">
        <div class="navbar-inner">
            <a href="/" class="brand">
                <img src="{{ asset('images/logo.png') }}" alt="Logo TEMPA" style="height: 30px;">
            </a>
            <nav class="nav-links">
                <a href="/">Beranda</a>
                <a href="{{ route('findings.index') }}" class="active">Temukan Partner</a>
                <a href="#">Dashboard Tim</a>
                <a href="#">Chat</a>
            </nav>
            <div class="nav-actions">
                @auth
                    <span class="user-greeting">Halo, {{ Auth::user()->name }}</span>
                    <form action="{{ route('logout') }}" method="POST" style="display:inline;">
                        @csrf
                        <button type="submit" class="btn-logout">Keluar</button>
                    </form>
                    <a href="{{ route('findings.create') }}" class="btn-primary">+ Buat Finding Partner</a>
                @else
                    <a href="{{ route('login') }}" class="link-login">Masuk</a>
                    <a href="{{ route('findings.create') }}" class="btn-primary">+ Buat Finding Partner</a>
                @endauth
            </div>
        </div>
    </header>

    <main class="container">
        <div class="badge-pill">DISCOVER & FILTER</div>
        <h1 class="page-title">Temukan Partner Andalan</h1>
        <p class="page-subtitle">Cari lowongan anggota tim berdasarkan kompetisi, kategori, keterampilan, tahap ide, dan status.</p>

        @if (session('success'))
            <div class="alert-success">{{ session('success') }}</div>
        @endif

        <form action="{{ route('findings.index') }}" method="GET" class="filter-section">
            <div class="search-box">
                <span class="search-icon">🔍</span>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari kompetisi atau keahlian (misal: Gemastik, Flutter, Data Science)">
                <button type="submit" class="btn-search">Cari</button>
            </div>

            <div class="category-chips">
                @php
                    $categories = ['Semua', 'Kompetisi Teknologi', 'Kompetisi Bisnis', 'Lomba Karya Tulis', 'Desain'];
                    $activeCat = request('kategori', 'Semua');
                @endphp
                @foreach ($categories as $cat)
                    <button type="submit" name="kategori" value="{{ $cat }}" class="chip {{ $activeCat === $cat ? 'active' : '' }}">
                        {{ $cat }}
                    </button>
                @endforeach
            </div>

            <div class="sub-filters">
                <div class="filter-group">
                    <label>Keterampilan</label>
                    <select name="keterampilan" onchange="this.form.submit()">
                        <option value="Semua">Semua</option>
                        @foreach (['UI/UX Design', 'Fullstack Dev', 'Machine Learning Engineer', 'Mobile Dev (Flutter)', 'Backend Developer', 'Pitching & Presentasi', 'Business Analyst', 'Desain Grafis', 'Analis Data', 'Penulis Ilmiah', 'Visual Designer', 'UX Researcher', 'Front-end Developer', 'Data Scientist', 'Data Engineer'] as $skill)
                            <option value="{{ $skill }}" {{ request('keterampilan') === $skill ? 'selected' : '' }}>{{ $skill }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="filter-group">
                    <label>Tahap ide</label>
                    <select name="tahap_ide" onchange="this.form.submit()">
                        <option value="Semua">Semua</option>
                        @foreach (['Ide', 'Prototipe', 'MVP / Produk'] as $tahap)
                            <option value="{{ $tahap }}" {{ request('tahap_ide') === $tahap ? 'selected' : '' }}>{{ $tahap }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="filter-group">
                    <label>Status</label>
                    <select name="status" onchange="this.form.submit()">
                        <option value="published" {{ request('status', 'published') === 'published' ? 'selected' : '' }}>Masih dibuka</option>
                        <option value="closed" {{ request('status') === 'closed' ? 'selected' : '' }}>Ditutup</option>
                    </select>
                </div>
            </div>
        </form>

        <p class="result-count">Menampilkan {{ $findings->count() }} lowongan Finding Partner</p>

        <div class="cards-grid">
            @forelse ($findings as $item)
                <article class="finding-card">
                    <div class="card-tags">
                        <span class="tag tag-purple">{{ $item->nama_kompetisi }}</span>
                        <span class="tag tag-blue">{{ $item->kategori }}</span>
                        <span class="tag tag-amber">Tahap: {{ $item->tahap_ide }}</span>
                    </div>

                    <h3 class="card-title">{{ $item->judul_tim }}</h3>
                    <p class="card-leader">Ketua tim: {{ $item->user->name ?? 'Mahasiswa UB' }}</p>

                    <div class="roles-label">PERAN YANG DIBUTUHKAN</div>
                    <div class="roles-list">
                        @foreach ($item->keterampilan as $role)
                            <div class="role-row {{ ($role['status'] ?? 'Dibutuhkan') === 'Terpenuhi' ? 'filled' : 'needed' }}">
                                <span>{{ $role['nama'] }}</span>
                                <span class="role-status">{{ $role['status'] ?? 'Dibutuhkan' }}</span>
                            </div>
                        @endforeach
                    </div>

                    <div class="card-meta">
                        <span>{{ $item->anggota_terkumpul }}/{{ $item->anggota_dibutuhkan }} anggota terkumpul</span>
                        <span>Ditutup {{ max(0, (int) \Carbon\Carbon::now()->diffInDays($item->tenggat_pengajuan, false)) }} hari lagi</span>
                    </div>

                    <div class="progress-bar">
                        @php
                            $total = max(1, $item->anggota_dibutuhkan);
                            $pct = min(100, round(($item->anggota_terkumpul / $total) * 100));
                        @endphp
                        <div class="progress-fill" style="width: {{ $pct }}%;"></div>
                    </div>

                    <div class="card-actions">
                        <a href="#" class="btn-outline">Lihat Detail</a>
                        <a href="#" class="btn-solid">Ajukan Diri</a>
                    </div>
                </article>
            @empty
                <div class="empty-state">
                    Belum ada lowongan Finding Partner yang sesuai dengan filter pencarianmu.
                </div>
            @endforelse
        </div>
    </main>

    <footer class="footer">
        <span class="footer-brand">TEMPA</span>
        <span class="footer-tagline">Temukan Partner. Wujudkan Prestasi.</span>
    </footer>
</body>
</html>