<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class KategoriSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Permission::create(['name' => 'kategori']);
        User::where('username', 'admin')->first();
        $role = Role::where('name', 'Admin')->first();

        $permissions = Permission::pluck('id','id')->all();
        $role->syncPermissions($permissions);
    }
}
