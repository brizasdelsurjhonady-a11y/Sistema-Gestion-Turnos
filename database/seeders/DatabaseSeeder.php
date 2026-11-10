<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        $permissions = [
            'dashboard.ver',
            'usuarios.ver',
            'usuarios.crear',
            'usuarios.editar',
            'usuarios.eliminar',
            'servicios.ver',
            'servicios.crear',
            'servicios.editar',
            'servicios.eliminar',
            'ventanillas.ver',
            'ventanillas.gestionar',
            'turnos.ver',
            'turnos.crear',
            'turnos.llamar',
            'turnos.iniciar',
            'turnos.finalizar',
            'turnos.no_presentado',
            'turnos.transferir',
            'turnos.ver-propios',
            'reportes.ver',
            'configuracion.gestionar',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate([
                'name' => $permission,
                'guard_name' => 'web',
            ]);
        }

        $adminRole = Role::firstOrCreate([
            'name' => 'Administrador',
            'guard_name' => 'web',
        ]);

        $operatorRole = Role::firstOrCreate([
            'name' => 'Operador',
            'guard_name' => 'web',
        ]);

        $citizenRole = Role::firstOrCreate([
            'name' => 'Ciudadano',
            'guard_name' => 'web',
        ]);

        $adminRole->syncPermissions($permissions);

        $operatorRole->syncPermissions([
            'dashboard.ver',
            'servicios.ver',
            'ventanillas.ver',
            'turnos.ver',
            'turnos.llamar',
            'turnos.iniciar',
            'turnos.finalizar',
            'turnos.no_presentado',
            'turnos.transferir',
        ]);

        $citizenRole->syncPermissions([
            'turnos.crear',
            'turnos.ver-propios',
        ]);

        $email = env('ADMIN_EMAIL');
        $name = env('ADMIN_NAME');
        $password = env('ADMIN_PASSWORD');

        if (!$email || !$name || !$password || strlen($password) < 12) {
            throw new \RuntimeException(
                'Configura ADMIN_NAME, ADMIN_EMAIL y ADMIN_PASSWORD en .env. La contraseña requiere al menos 12 caracteres.'
            );
        }

        $admin = User::firstOrCreate(
            ['email' => $email],
            [
                'name' => $name,
                'password' => $password,
                'email_verified_at' => now(),
            ]
        );

        $admin->assignRole($adminRole);

        app(PermissionRegistrar::class)->forgetCachedPermissions();

        $this->command->info('Roles y permisos creados correctamente.');
        $this->command->info('Administrador configurado: ' . $admin->email);
    }
}