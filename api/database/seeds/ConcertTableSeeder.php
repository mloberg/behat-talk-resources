<?php

use App\Models\Concert;
use Illuminate\Database\Seeder;

class ConcertTableSeeder extends Seeder
{
    public function run()
    {
        Concert::create([
            'title'       => 'Basilica Block Party',
            'description' => 'Join us July 10 & 11, 2015 for the annual Basilica Block Party featuring Weezer, Wilco, O.A.R., Fitz and the Tantrums, Zoo Animal, and more.',
            'date'        => '2015-07-10 17:00:00',
        ]);
    }
}
