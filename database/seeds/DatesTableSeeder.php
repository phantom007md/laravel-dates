<?php

use Illuminate\Database\Seeder;
use App\Date;

class DatesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('dates')->insert([
            [
                'length' => 20,
                'dateTime' => new DateTime('now'),
                'user_id' => 1,
                'payment_id' => 1,
            ],
            [
                'length' => 40,
                'dateTime' => new DateTime('tomorrow'),
                'user_id' => 1,
                'payment_id' => 1,
            ],
        ]);
    }
}
