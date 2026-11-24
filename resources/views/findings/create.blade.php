<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buat Finding Partner - TEMPA</title>
    <link rel="stylesheet" href="{{ asset('css/findings.css') }}">
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
        <div class="badge-pill">01 - PUBLISH</div>
        <h1 class="page-title">Buat Finding Partner</h1>
        <p class="page-subtitle">Cantumkan informasi kompetisi, jumlah anggota yang dibutuhkan, keterampilan, dan tenggat pengajuan agar calon anggota yang cocok bisa menemukanmu.</p>

        @if ($errors->any())
            <div class="alert-error">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="create-layout">
            <form action="{{ route('findings.store') }}" method="POST" class="form-card" id="createFindingForm">
                @csrf
                <div class="form-row-2">
                    <div class="form-group">
                        <label>Nama kompetisi</label>
                        <input type="text" name="nama_kompetisi" id="inputKompetisi" placeholder="Contoh: Gemastik" value="{{ old('nama_kompetisi') }}" required>
                    </div>
                    <div class="form-group">
                        <label>Kategori</label>
                        <select name="kategori" id="inputKategori">
                            <option value="Kompetisi Teknologi">Kompetisi Teknologi</option>
                            <option value="Kompetisi Bisnis">Kompetisi Bisnis</option>
                            <option value="Lomba Karya Tulis">Lomba Karya Tulis</option>
                            <option value="Desain">Desain</option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label>Judul tim atau ide</label>
                    <input type="text" name="judul_tim" id="inputJudul" placeholder="Contoh: Tim Ecovision - Solusi AI Pendeteksi Sampah Sungai" value="{{ old('judul_tim') }}" required>
                </div>

                <div class="form-row-3">
                    <div class="form-group">
                        <label>Tahap ide</label>
                        <select name="tahap_ide" id="inputTahap">
                            <option value="Ide">Ide</option>
                            <option value="Prototipe">Prototipe</option>
                            <option value="MVP / Produk">MVP / Produk</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Anggota dibutuhkan</label>
                        <input type="number" name="anggota_dibutuhkan" min="1" max="10" value="{{ old('anggota_dibutuhkan', 1) }}" required>
                    </div>
                    <div class="form-group">
                        <label>Tenggat pengajuan</label>
                        <input type="date" name="tenggat_pengajuan" value="{{ old('tenggat_pengajuan') }}" required>
                    </div>
                </div>

                <div class="form-group">
                    <div class="label-between">
                        <label>Keterampilan yang dibutuhkan</label>
                        <span id="skillCount" class="skill-counter">0 dipilih</span>
                    </div>
                    <div class="skill-pills" id="skillContainer">
                        @php
                            $skills = ['Machine Learning', 'Computer Vision', 'UI/UX Design', 'Frontend', 'Backend', 'Flutter', 'Data Science', 'Business Analyst', 'Pitching', 'Penulisan Ilmiah', 'Desain Grafis'];
                        @endphp
                        @foreach ($skills as $skill)
                            <label class="skill-checkbox">
                                <input type="checkbox" name="keterampilan[]" value="{{ $skill }}" class="skill-input">
                                <span>{{ $skill }}</span>
                            </label>
                        @endforeach
                    </div>

                    <div class="custom-skill-box">
                        <input type="text" id="customSkillInput" placeholder="Masukkan skill lain yang belum tersedia...">
                        <button type="button" id="addCustomSkillBtn" class="btn-add-skill">+ Tambah</button>
                    </div>
                </div>

                <div class="form-group">
                    <label>Kriteria dan deskripsi singkat</label>
                    <textarea name="deskripsi" id="inputDeskripsi" rows="4" placeholder="Ceritakan ide tim, target kompetisi, dan kriteria calon anggota." required>{{ old('deskripsi') }}</textarea>
                </div>

                <div class="form-actions">
                    <button type="submit" name="action_type" value="published" class="btn-primary">Publikasikan Finding Partner</button>
                    <button type="submit" name="action_type" value="draft" class="btn-secondary">Simpan Draft</button>
                </div>
            </form>

            <aside class="preview-wrapper">
                <div class="preview-card">
                    <div class="preview-header">PRATINJAU LOWONGAN</div>
                    <div class="card-tags">
                        <span class="tag tag-purple" id="prevKompetisi">Nama kompetisi</span>
                        <span class="tag tag-blue" id="prevKategori">Kompetisi Teknologi</span>
                    </div>
                    <h3 class="preview-title" id="prevJudul">Judul tim atau ide kamu</h3>
                    <div class="roles-label">KETERAMPILAN YANG DICARI</div>
                    <div class="preview-skills" id="prevSkills">
                        <span class="tag tag-amber">Belum ada skill dipilih</span>
                    </div>
                    <p class="preview-desc" id="prevDeskripsi">Calon anggota akan melihat peran, tenggat, dan tombol Ajukan Diri.</p>
                </div>
                <p class="preview-note">Setelah dipublikasikan, pengajuan masuk ke dashboard Kelola Calon Anggota dan dianalisis oleh Skill-Gap Analyzer.</p>
            </aside>
        </div>
    </main>

    <script>
        const inputKompetisi = document.getElementById('inputKompetisi');
        const inputKategori = document.getElementById('inputKategori');
        const inputJudul = document.getElementById('inputJudul');
        const inputDeskripsi = document.getElementById('inputDeskripsi');

        inputKompetisi.addEventListener('input', () => {
            document.getElementById('prevKompetisi').textContent = inputKompetisi.value || 'Nama kompetisi';
        });
        inputKategori.addEventListener('change', () => {
            document.getElementById('prevKategori').textContent = inputKategori.value;
        });
        inputJudul.addEventListener('input', () => {
            document.getElementById('prevJudul').textContent = inputJudul.value || 'Judul tim atau ide kamu';
        });
        inputDeskripsi.addEventListener('input', () => {
            document.getElementById('prevDeskripsi').textContent = inputDeskripsi.value || 'Calon anggota akan melihat peran, tenggat, dan tombol Ajukan Diri.';
        });

        function updateSkillsPreview() {
            const checked = Array.from(document.querySelectorAll('.skill-input:checked')).map(el => el.value);
            document.getElementById('skillCount').textContent = checked.length + ' dipilih';
            const prevSkills = document.getElementById('prevSkills');
            if (checked.length === 0) {
                prevSkills.innerHTML = '<span class="tag tag-amber">Belum ada skill dipilih</span>';
            } else {
                prevSkills.innerHTML = checked.map(s => `<span class="tag tag-amber">${s}</span>`).join('');
            }
        }

        document.getElementById('skillContainer').addEventListener('change', updateSkillsPreview);

        document.getElementById('addCustomSkillBtn').addEventListener('click', () => {
            const val = document.getElementById('customSkillInput').value.trim();
            if (!val) return;
            const label = document.createElement('label');
            label.className = 'skill-checkbox';
            label.innerHTML = `<input type="checkbox" name="keterampilan[]" value="${val}" class="skill-input" checked><span>${val}</span>`;
            document.getElementById('skillContainer').appendChild(label);
            document.getElementById('customSkillInput').value = '';
            updateSkillsPreview();
        });
    </script>
</body>
</html>