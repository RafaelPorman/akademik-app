<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Halaman: {{ $pageTitle }} | Aplikasi Akademik</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    @php
        $role = auth()->user()->role;
        $menusByRole = [
            'admin' => [
                ['name' => 'Admin', 'route' => 'admin'],
                ['name' => 'Manajemen Pengguna', 'route' => 'manajemen-pengguna'],
                ['name' => 'Kelola Materi', 'route' => 'kelola-materi'],
                ['name' => 'Kelola Nilai', 'route' => 'kelola-nilai'],
                ['name' => 'Lihat Materi', 'route' => 'lihat-materi'],
            ],
            'dosen' => [
                ['name' => 'Dashboard Dosen', 'route' => 'dosen'],
                ['name' => 'Kelola Materi', 'route' => 'kelola-materi'],
                ['name' => 'Kelola Nilai', 'route' => 'kelola-nilai'],
                ['name' => 'Lihat Materi', 'route' => 'lihat-materi'],
            ],
            'mahasiswa' => [
                ['name' => 'Dashboard Mahasiswa', 'route' => 'mahasiswa'],
                ['name' => 'Nilai Pribadi', 'route' => 'nilai-pribadi'],
                ['name' => 'Lihat Materi', 'route' => 'lihat-materi'],
            ],
        ];
        $allRoutes = [
            ['name' => 'Admin', 'route' => 'admin', 'roles' => 'admin'],
            ['name' => 'Manajemen Pengguna', 'route' => 'manajemen-pengguna', 'roles' => 'admin'],
            ['name' => 'Dosen', 'route' => 'dosen', 'roles' => 'dosen'],
            ['name' => 'Mahasiswa', 'route' => 'mahasiswa', 'roles' => 'mahasiswa'],
            ['name' => 'Nilai Pribadi', 'route' => 'nilai-pribadi', 'roles' => 'mahasiswa'],
            ['name' => 'Kelola Materi', 'route' => 'kelola-materi', 'roles' => 'admin, dosen'],
            ['name' => 'Kelola Nilai', 'route' => 'kelola-nilai', 'roles' => 'admin, dosen'],
            ['name' => 'Lihat Materi', 'route' => 'lihat-materi', 'roles' => 'admin, dosen, mahasiswa'],
        ];
        $materials = [
            ['title' => 'Pengantar Pemrograman', 'course' => 'Algoritma dan Pemrograman', 'date' => '2026-11-10'],
            ['title' => 'Normalisasi Basis Data', 'course' => 'Sistem Basis Data', 'date' => '2026-11-12'],
            ['title' => 'Konsep Jaringan Komputer', 'course' => 'Jaringan Komputer', 'date' => '2026-11-15'],
        ];
    @endphp

    <main class="container py-5">
        <header class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
            <div>
                <p class="text-uppercase text-secondary small fw-semibold mb-1">Aplikasi Akademik</p>
                <h1 class="h2 mb-2">Halaman: {{ $pageTitle }}</h1>
                <p class="text-secondary mb-0">
                    Halo, {{ auth()->user()->name }}.
                    Role aktif: <span class="badge text-bg-primary">{{ $role }}</span>
                </p>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn btn-outline-danger">Logout</button>
            </form>
        </header>

        @if ($pageKey === 'users')
            <section class="card shadow-sm mb-4">
                <div class="card-body">
                    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
                        <div>
                            <h2 class="h5 mb-1">Daftar Pengguna</h2>
                            <p class="text-secondary mb-0">Akun admin, dosen, dan mahasiswa.</p>
                        </div>
                        <button type="button" class="btn btn-primary">Tambah User</button>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-striped align-middle mb-0">
                            <thead><tr><th>Nama</th><th>Email</th><th>Role</th><th>Aksi</th></tr></thead>
                            <tbody>
                                @forelse ($users as $user)
                                    <tr>
                                        <td>{{ $user->name }}</td>
                                        <td>{{ $user->email }}</td>
                                        <td><span class="badge text-bg-secondary">{{ $user->role }}</span></td>
                                        <td><button type="button" class="btn btn-sm btn-outline-danger">Hapus</button></td>
                                    </tr>
                                @empty
                                    <tr><td colspan="4" class="text-center text-secondary">Belum ada data pengguna. Jalankan php artisan db:seed.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>
        @elseif ($pageKey === 'manage-materials')
            <section class="card shadow-sm mb-4">
                <div class="card-body">
                    <h2 class="h5 mb-3">Upload Materi Kuliah</h2>
                    <form>
                        <div class="row g-3 align-items-end">
                            <div class="col-md-4">
                                <label for="material-title" class="form-label">Judul Materi</label>
                                <input id="material-title" class="form-control" placeholder="Contoh: Pengantar Pemrograman">
                            </div>
                            <div class="col-md-4">
                                <label for="material-course" class="form-label">Mata Kuliah</label>
                                <input id="material-course" class="form-control" placeholder="Pilih atau masukkan mata kuliah">
                            </div>
                            <div class="col-md-3">
                                <label for="material-file" class="form-label">File Materi</label>
                                <input id="material-file" type="file" class="form-control">
                            </div>
                            <div class="col-md-1">
                                <button type="button" class="btn btn-primary">Upload</button>
                            </div>
                        </div>
                        <p class="form-text mb-0 mt-2">Form ini hanya simulasi; data tidak disimpan.</p>
                    </form>
                </div>
            </section>
            <section class="card shadow-sm mb-4">
                <div class="card-body">
                    <h2 class="h5 mb-3">Daftar Materi Kuliah</h2>
                    <div class="table-responsive">
                        <table class="table table-striped align-middle mb-0">
                            <thead><tr><th>Judul Materi</th><th>Mata Kuliah</th><th>Tanggal Upload</th><th>Aksi</th></tr></thead>
                            <tbody>
                                @foreach ($materials as $material)
                                    <tr>
                                        <td>{{ $material['title'] }}</td><td>{{ $material['course'] }}</td><td>{{ $material['date'] }}</td>
                                        <td class="text-nowrap">
                                            <button type="button" class="btn btn-sm btn-outline-primary">Edit</button>
                                            <button type="button" class="btn btn-sm btn-outline-danger">Hapus</button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>
        @elseif ($pageKey === 'manage-grades')
            <section class="card shadow-sm mb-4">
                <div class="card-body">
                    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
                        <div>
                            <h2 class="h5 mb-1">Daftar Nilai Mahasiswa</h2>
                            <p class="text-secondary mb-0">Data dummy untuk simulasi pengelolaan nilai.</p>
                        </div>
                        <button type="button" class="btn btn-primary">Simpan Nilai</button>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-striped align-middle mb-0">
                            <thead><tr><th>NIM</th><th>Nama Mahasiswa</th><th>Mata Kuliah</th><th>Nilai Akhir</th></tr></thead>
                            <tbody>
                                <tr><td>20230001</td><td>Andi Pratama</td><td>Algoritma dan Pemrograman</td><td>A</td></tr>
                                <tr><td>20230002</td><td>Siti Rahma</td><td>Sistem Basis Data</td><td>A-</td></tr>
                                <tr><td>20230003</td><td>Budi Santoso</td><td>Jaringan Komputer</td><td>B+</td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>
        @elseif ($pageKey === 'lecturer')
            <section class="row g-3 mb-4">
                <div class="col-md-4">
                    <article class="card shadow-sm h-100"><div class="card-body">
                        <h2 class="h6 text-secondary">Mata Kuliah Diampu</h2>
                        <p class="display-6 mb-0">3</p>
                    </div></article>
                </div>
                <div class="col-md-8">
                    <article class="card shadow-sm h-100"><div class="card-body">
                        <h2 class="h5">Jadwal Mengajar</h2>
                        <ul class="mb-0">
                            <li>Senin, 08.00 — Algoritma dan Pemrograman (Ruang A-201)</li>
                            <li>Rabu, 10.00 — Sistem Basis Data (Ruang B-105)</li>
                            <li>Jumat, 13.00 — Jaringan Komputer (Ruang A-302)</li>
                        </ul>
                    </div></article>
                </div>
            </section>
        @elseif ($pageKey === 'student')
            <section class="card shadow-sm mb-4">
                <div class="card-body">
                    <h2 class="h5 mb-3">Ringkasan Profil Mahasiswa</h2>
                    <dl class="row mb-0">
                        <dt class="col-sm-3">Nama</dt><dd class="col-sm-9">{{ auth()->user()->name }}</dd>
                        <dt class="col-sm-3">Email</dt><dd class="col-sm-9">{{ auth()->user()->email }}</dd>
                        <dt class="col-sm-3">Semester Aktif</dt><dd class="col-sm-9">5 (Ganjil 2026/2027)</dd>
                        <dt class="col-sm-3">IPK Sementara</dt><dd class="col-sm-9">3,72</dd>
                    </dl>
                </div>
            </section>
        @elseif ($pageKey === 'view-materials')
            <section class="card shadow-sm mb-4">
                <div class="card-body">
                    <h2 class="h5 mb-3">Materi Kuliah</h2>
                    <p class="text-secondary">Daftar materi read-only. Tidak tersedia aksi edit atau hapus.</p>
                    <div class="list-group">
                        @foreach ($materials as $material)
                            <div class="list-group-item d-flex flex-wrap justify-content-between align-items-center gap-2">
                                <div>
                                    <h3 class="h6 mb-1">{{ $material['title'] }}</h3>
                                    <span class="text-secondary">{{ $material['course'] }} · Diunggah {{ $material['date'] }}</span>
                                </div>
                                <button type="button" class="btn btn-sm btn-outline-primary">Baca / Download</button>
                            </div>
                        @endforeach
                    </div>
                </div>
            </section>
        @elseif ($pageKey === 'personal-grades')
            <section class="card shadow-sm mb-4">
                <div class="card-body">
                    <h2 class="h5 mb-1">Transkrip Nilai Pribadi</h2>
                    <p class="text-secondary">Nilai milik {{ auth()->user()->name }} ({{ auth()->user()->email }}).</p>
                    <div class="table-responsive">
                        <table class="table table-striped align-middle mb-0">
                            <thead><tr><th>NIM</th><th>Nama Mahasiswa</th><th>Mata Kuliah</th><th>Nilai Akhir</th></tr></thead>
                            <tbody>
                                <tr><td>20230001</td><td>{{ auth()->user()->name }}</td><td>Algoritma dan Pemrograman</td><td>A</td></tr>
                                <tr><td>20230001</td><td>{{ auth()->user()->name }}</td><td>Sistem Basis Data</td><td>A-</td></tr>
                                <tr><td>20230001</td><td>{{ auth()->user()->name }}</td><td>Jaringan Komputer</td><td>B+</td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>
        @endif

        <section class="card shadow-sm mb-4">
            <div class="card-body">
                <h2 class="h5 mb-3">Menu Sesuai Role Anda</h2>
                <div class="d-flex flex-wrap gap-2">
                    @foreach ($menusByRole[$role] as $menu)
                        <a href="{{ route($menu['route']) }}" class="btn btn-outline-primary">{{ $menu['name'] }}</a>
                    @endforeach
                </div>
            </div>
        </section>

        <section class="card border-warning shadow-sm">
            <div class="card-body">
                <h2 class="h5 mb-2">Panel Uji Middleware / Simulasi Akses Semua Route</h2>
                <p class="text-secondary">Klik route di bawah untuk menguji akses yang diizinkan atau respons 403 Forbidden.</p>
                <div class="d-flex flex-wrap gap-2">
                    @foreach ($allRoutes as $item)
                        <a href="{{ route($item['route']) }}" class="btn btn-sm btn-outline-secondary">
                            {{ $item['name'] }} <span class="text-muted">(role: {{ $item['roles'] }})</span>
                        </a>
                    @endforeach
                </div>
            </div>
        </section>
    </main>
</body>
</html>
