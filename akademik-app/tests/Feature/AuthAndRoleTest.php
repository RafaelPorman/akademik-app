<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthAndRoleTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_page_is_available_to_guests(): void
    {
        $this->get('/login')->assertOk()->assertSee('Login Akademik');
    }

    public function test_login_redirects_users_to_their_role_dashboard(): void
    {
        $user = User::factory()->create([
            'email' => 'admin@kampus.ac.id',
            'password' => 'password',
            'role' => 'admin',
        ]);

        $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ])->assertRedirect('/admin');

        $this->assertAuthenticatedAs($user);
    }

    public function test_invalid_credentials_return_to_login_with_an_error(): void
    {
        $this->from('/login')->post('/login', [
            'email' => 'admin@kampus.ac.id',
            'password' => 'wrong-password',
        ])->assertRedirect('/login')
            ->assertSessionHasErrors('email');
    }

    public function test_role_middleware_allows_and_blocks_routes(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $this->actingAs($admin);

        $this->get('/admin')
            ->assertOk()
            ->assertSee($admin->name)
            ->assertSee('Halaman: Admin')
            ->assertSee('Daftar Pengguna')
            ->assertSee('Tambah User')
            ->assertSee(route('manajemen-pengguna'), false);
        $this->get('/manajemen-pengguna')
            ->assertOk()
            ->assertSee('Halaman: Manajemen Pengguna');
        $this->get('/kelola-materi')
            ->assertOk()
            ->assertSee('Upload Materi Kuliah')
            ->assertSee('Tanggal Upload');
        $this->get('/kelola-nilai')
            ->assertOk()
            ->assertSee('Daftar Nilai Mahasiswa')
            ->assertSee('Simpan Nilai');
        $this->get('/lihat-materi')
            ->assertOk()
            ->assertSee('Materi Kuliah')
            ->assertSee('read-only');
        $this->get('/dosen')->assertForbidden();
        $this->get('/nilai-pribadi')->assertForbidden();
    }

    public function test_dosen_and_mahasiswa_pages_show_role_specific_content(): void
    {
        $lecturer = User::factory()->create(['role' => 'dosen']);
        $this->actingAs($lecturer)
            ->get('/dosen')
            ->assertOk()
            ->assertSee('Halaman: Dosen')
            ->assertSee('Jadwal Mengajar')
            ->assertSee('Mata Kuliah Diampu');

        $student = User::factory()->create([
            'name' => 'Mahasiswa Contoh',
            'email' => 'saya@kampus.ac.id',
            'role' => 'mahasiswa',
        ]);
        $this->actingAs($student)
            ->get('/mahasiswa')
            ->assertOk()
            ->assertSee('Halaman: Mahasiswa')
            ->assertSee('Semester Aktif')
            ->assertSee('IPK Sementara');

        $this->get('/nilai-pribadi')
            ->assertOk()
            ->assertSee('Halaman: Nilai Pribadi')
            ->assertSee('Mahasiswa Contoh')
            ->assertSee('saya@kampus.ac.id')
            ->assertDontSee('Andi Pratama');
    }

    public function test_logout_invalidates_the_authenticated_session(): void
    {
        $user = User::factory()->create(['role' => 'mahasiswa']);

        $this->actingAs($user)
            ->post('/logout')
            ->assertRedirect('/login');

        $this->assertGuest();
    }

    public function test_database_seeder_creates_the_three_test_accounts(): void
    {
        $this->seed();

        foreach ([
            'admin@kampus.ac.id' => 'admin',
            'dosen@kampus.ac.id' => 'dosen',
            'mahasiswa@kampus.ac.id' => 'mahasiswa',
        ] as $email => $role) {
            $user = User::where('email', $email)->first();

            $this->assertNotNull($user);
            $this->assertSame($role, $user->role);
            $this->assertTrue(password_verify('password', $user->password));
        }
    }
}
