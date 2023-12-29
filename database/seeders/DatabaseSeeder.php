<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Event;
use App\Models\EventTicketType;
use App\Models\User;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {

        User::factory(1)->create();
        Event::factory(10)->create()->each(function($event){
            EventTicketType::factory(rand(3,5))->create(['event_id'=>$event->id]);
        });

        $this->call([MpesaIpAddressSeeder::class,]);
    }
}
