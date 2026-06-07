<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
         // Create Roles
    $admin = Role::create(['name' => 'admin']);
    $editor = Role::create(['name' => 'editor']);
    $user = Role::create(['name' => 'user']);

    // Create Permissions
    $createPost = Permission::create(['name' => 'create-post']);
    $editPost = Permission::create(['name' => 'edit-post']);
    $deletePost = Permission::create(['name' => 'delete-post']);

    // Assign Permissions to Roles
    $admin->permissions()->attach([$createPost->id, $editPost->id, $deletePost->id]);
    $editor->permissions()->attach([$createPost->id, $editPost->id]);
    $user->permissions()->attach([$createPost->id]);
}

    
}
