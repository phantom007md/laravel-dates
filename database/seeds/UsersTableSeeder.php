<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            [
                'name'=>'سید محمد حسین طباطبائی',
                'email'=>'mohamad.d007@gmail.com',
                'phone'=>'09335177071',
                'isAdmin'=> true ,
            ],
        ]);
    }
}
