<?php

use Illuminate\Database\Seeder;

use Illuminate\Support\Facades\DB;

class EmployeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('employees')->insert([
            'code' => 'KRY0001',
            'name' => 'Karyawan 1',
            'phone' => '087665124',
            'address' => 'Jalan 123, Jakarta',
            'date_of_birth' => '2020-09-05',
            'email' => 'employee@gmail.com',
            'password' => bcrypt('employee123')
        ]);
    }
}
