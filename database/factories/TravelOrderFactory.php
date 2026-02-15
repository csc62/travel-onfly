<?php

namespace Database\Factories;

use App\Models\TravelOrder;
use Illuminate\Database\Eloquent\Factories\Factory;

class TravelOrderFactory extends Factory
{
    protected $model = TravelOrder::class;

    public function definition()
    {
        return [
            'user_id' => null, // será setado no teste
            'destination' => $this->faker->city(),
            'departure_date' => $this->faker->date(),
            'return_date' => $this->faker->date('+1 week'),
            'status' => 'solicitado',
        ];
    }
}
