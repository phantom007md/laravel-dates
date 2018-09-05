<?php

use Illuminate\Database\Seeder;

class TopicsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('topics')->insert([
            [
                'topic' => 'HTML,CSS',
                'name' => 'دوره HTML CSS و طراحی قالب',
                'basePrice' => 50,
            ],
            [
                'topic' => 'React',
                'name' => 'دوره ریکت',
                'basePrice' => 70,
            ],
            [
                'topic' => 'JavaScript Basics',
                'name' => 'دوره جاوا اسکریپت مقدماتی',
                'basePrice' => 70,
            ],
            [
                'topic' => 'JavaScript Pro',
                'name' => 'دوره جاوا اسکریپت پیشرفته',
                'basePrice' => 50,
            ],
        ]);
    }
}
