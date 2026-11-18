<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    private const PAGES = [
        'admin' => ['title' => 'Admin', 'key' => 'users'],
        'manajemen-pengguna' => ['title' => 'Manajemen Pengguna', 'key' => 'users'],
        'kelola-materi' => ['title' => 'Kelola Materi', 'key' => 'manage-materials'],
        'kelola-nilai' => ['title' => 'Kelola Nilai', 'key' => 'manage-grades'],
        'dosen' => ['title' => 'Dosen', 'key' => 'lecturer'],
        'mahasiswa' => ['title' => 'Mahasiswa', 'key' => 'student'],
        'lihat-materi' => ['title' => 'Lihat Materi', 'key' => 'view-materials'],
        'nilai-pribadi' => ['title' => 'Nilai Pribadi', 'key' => 'personal-grades'],
    ];

    public function __invoke(Request $request): View
    {
        $page = self::PAGES[$request->route()->getName()];
        $data = ['pageTitle' => $page['title'], 'pageKey' => $page['key']];

        if ($page['key'] === 'users') {
            $data['users'] = User::query()
                ->whereIn('role', ['admin', 'dosen', 'mahasiswa'])
                ->orderBy('role')
                ->orderBy('name')
                ->get(['name', 'email', 'role']);
        }

        return view('dashboard', $data);
    }
}
