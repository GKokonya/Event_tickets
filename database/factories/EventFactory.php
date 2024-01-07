<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Event>
 */
class EventFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            //
            'organizer_id' => 2, 
            'title'=>fake()->realText(rand(10, 20)),
            'description'=>fake()->text(),
            'town'=> fake()->city(),
            'country'=>fake()->country(),
            'organizer'=>fake()->company(),
            'venue'=>'Safari Park',
            'start_date'=>date("Y/m/d"),
            'end_date'=> date("Y/m/d"),
            'start_time'=> date("h:i"),
            'end_time'=> date("h:i"),
            'image'=>'public/images/5f9e854ad7a49262808912.jpg',
            'created_at'=>now(),
            'updated_at'=>now()
        ];
    }
}
