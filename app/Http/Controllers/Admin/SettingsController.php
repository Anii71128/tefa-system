<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class SettingsController extends Controller
{
    public function index(): View
    {
        $user = auth()->user();
        $totalUsers = User::count();
        $superAdmins = User::where('role', 'admin')->count();
        $teachers = User::where('role', 'guru')->count();
        $students = User::where('role', 'siswa')->count();
        $customers = User::where('role', 'user')->count();
        $databaseSize = 0;

        try {
            $databaseSize = DB::selectOne(
                'SELECT SUM(data_length + index_length) AS size FROM information_schema.tables WHERE table_schema = DATABASE()'
            )->size ?? 0;
        } catch (\Throwable $e) {
            $databaseSize = 0;
        }

        $systemSettings = DB::table('admin_settings')->pluck('value', 'key')->toArray();

        return view('admin.settings.index', compact(
            'user', 'totalUsers', 'superAdmins', 'teachers', 'students', 'customers', 'databaseSize', 'systemSettings'
        ));
    }

    public function updateProfile(Request $request): RedirectResponse
    {
        $user = auth()->user();
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,' . $user->id],
        ]);

        $user->name = $validated['name'];
        if ($user->email !== $validated['email']) {
            $user->email = $validated['email'];
            $user->email_verified_at = null;
        }
        $user->save();

        return redirect()->route('admin.settings.index')->with('success', 'Profil administrator berhasil diperbarui.');
    }

    public function updatePassword(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user = auth()->user();
        $user->password = Hash::make($validated['password']);
        $user->save();

        return redirect()->route('admin.settings.index')->with('success', 'Kata sandi berhasil diperbarui.');
    }


    /** Save Admin-only system preferences (does not activate operational workflows). */
    public function updateSystem(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'school_name' => ['required', 'string', 'max:255'],
            'tefa_name' => ['required', 'string', 'max:255'],
            'automation' => ['nullable', 'array'],
            'automation.*' => ['in:order_notifications,production_automation,stock_alerts,user_audit'],
        ]);

        $allowed = ['order_notifications', 'production_automation', 'stock_alerts', 'user_audit'];
        $enabled = array_values(array_intersect($validated['automation'] ?? [], $allowed));

        foreach ([
            'school_name' => $validated['school_name'],
            'tefa_name' => $validated['tefa_name'],
            'automation' => json_encode($enabled),
        ] as $key => $value) {
            DB::table('admin_settings')->updateOrInsert(['key' => $key], [
                'value' => $value, 'updated_at' => now(), 'created_at' => now(),
            ]);
        }

        return redirect()->route('admin.settings.index')->with('success', 'Preferensi sistem berhasil disimpan. Pengaturan automasi tersimpan sebagai preferensi dan belum menjalankan proses operasional.');
    }

    /** Download an SQL dump of the active MySQL/MariaDB database. */
    public function backup(): StreamedResponse
    {
        $tables = DB::select('SHOW FULL TABLES WHERE Table_type = ?', ['BASE TABLE']);
        $tableKey = 'Tables_in_' . DB::getDatabaseName();
        $pdo = DB::connection()->getPdo();
        $temp = tempnam(sys_get_temp_dir(), 'tefa_backup_');

        if ($temp === false) {
            abort(500, 'Tidak dapat membuat file backup sementara.');
        }

        $handle = fopen($temp, 'wb');
        if ($handle === false) {
            @unlink($temp);
            abort(500, 'Tidak dapat menulis file backup sementara.');
        }

        try {
            fwrite($handle, "-- TEFA Database Backup\n-- Generated: " . now()->toDateTimeString() . "\n\nSET FOREIGN_KEY_CHECKS=0;\n\n");

            foreach ($tables as $tableInfo) {
                $table = $tableInfo->{$tableKey};
                $safeTable = str_replace('`', '``', $table);
                $create = DB::select("SHOW CREATE TABLE `{$safeTable}`");
                $createSql = $create[0]->{'Create Table'} ?? null;
                if (!$createSql) continue;

                fwrite($handle, "\n-- Table: `{$table}`\nDROP TABLE IF EXISTS `{$safeTable}`;\n{$createSql};\n\n");

                DB::table($table)->orderBy(DB::raw('1'))->chunk(500, function ($rows) use ($handle, $pdo, $safeTable) {
                    foreach ($rows as $row) {
                        $values = [];
                        foreach ((array) $row as $value) {
                            if ($value === null) {
                                $values[] = 'NULL';
                            } elseif (is_bool($value)) {
                                $values[] = $value ? '1' : '0';
                            } else {
                                $values[] = $pdo->quote((string) $value);
                            }
                        }
                        fwrite($handle, "INSERT INTO `{$safeTable}` VALUES (" . implode(', ', $values) . ");\n");
                    }
                });
                fwrite($handle, "\n");
            }

            fwrite($handle, "\nSET FOREIGN_KEY_CHECKS=1;\n");
        } catch (\Throwable $e) {
            fclose($handle);
            @unlink($temp);
            throw $e;
        }

        fclose($handle);
        $filename = 'tefa-database-backup-' . now()->format('Y-m-d_H-i-s') . '.sql';

        return response()->streamDownload(function () use ($temp) {
            readfile($temp);
            @unlink($temp);
        }, $filename, ['Content-Type' => 'application/sql']);
    }
}
