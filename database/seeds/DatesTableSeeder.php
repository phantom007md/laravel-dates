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
                'start_date' => new DateTime('now'),
                'user_id' => 1,
                'topic_id' => 1,
                'payment_id' => 1,
            ],
            [
                'length' => 40,
                'start_date' => new DateTime('tomorrow'),
                'user_id' => 1,
                'topic_id' => 1,
                'payment_id' => 1,
            ],
        ]);
    }
}
