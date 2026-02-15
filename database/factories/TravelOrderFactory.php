<?php

namespace Database\Factories;

use App\Models\TravelOrder;
use Illuminate\Database\Eloquent\Factories\Factory;

class TravelOrderFactory extends Factory
{
    protected $model = TravelOrder::class;

    public function definition()
    {
        $departure = $this->faker->dateTimeBetween('now', '+1 month');

        return [
            'user_id' => null,
            'destination' => $this->faker->city(),
            'departure_date' => $departure->format('Y-m-d'),
            'return_date' => $departure->modify('+' . rand(3, 10) . ' days')->format('Y-m-d'),
            'status' => 'solicitado',
        ];
    }
}
