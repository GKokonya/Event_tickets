<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Model\EventTicketType>
 */
class EventTicketTypeFactory extends Factory
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
            'title'=>fake()->realText(rand(10, 15)),
            'unit_price'=>rand(50,100),
            'quantity'=>10,
            'start_date'=>date("Y/m/d"),
            'end_date'=>date("Y/m/d")
        ];
    }
}
