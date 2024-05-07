<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use App\Models\Book;


class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        //Create Books
        Book::factory(100)->create();
        
        //Create Users
        $user_admin = User::create([
            'identification' => '144048955', 
            'first_name' => 'admin',
            'last_name' => 'biblionacho',
            'email' => 'admin@biblionacho.com',
            'password' => bcrypt('biblionacho'),
        ]);

        $user_employee =  User::create([
            'identification' => '158048955', 
            'first_name' => 'employee',
            'last_name' => 'biblionacho',
            'email' => 'employee@biblionacho.com',
            'password' => bcrypt('employee'),
        ]);

        $user_affiliate =  User::create([
            'identification' => '35880485', 
            'first_name' => 'affiliate',
            'last_name' => 'biblionacho',
            'email' => 'affiliate@biblionacho.com',
            'password' => bcrypt('affiliate'),
        ]);

        $user_guest = User::create([
            'identification' => '11448975',
            'first_name' => 'guest',
            'last_name' => 'biblionacho',
            'email' => 'guest@biblionacho.com',
            'password' => bcrypt('guest'),
        ]);

        //Create Roles
        $admin = Role::create(['name' => 'admin']);
        $employee = Role::create(['name' => 'employee']);
        $affiliate = Role::create(['name' => 'affiliate']);
        $guest = Role::create(['name' => 'guest']);

        //Assign Roles
        $user_admin->assignRole($admin);
        $user_employee->assignRole($employee);
        $user_affiliate->assignRole($affiliate);
        $user_guest->assignRole($guest);

    }
}
