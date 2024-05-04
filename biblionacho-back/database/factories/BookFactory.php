<?php

namespace Database\Factories;

use App\Models\Book;
use Illuminate\Database\Eloquent\Factories\Factory;

class BookFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Book::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'isbn' => $this->faker->unique()->bothify('######????'),
            'title' => $this->faker->sentence,
            'author' => $this->faker->name,
            'editorial' => $this->faker->company,
            'edition' => $this->faker->numberBetween(1, 10),
            'year' => $this->faker->year,
            'language' => $this->faker->languageCode,
            'pages' => $this->faker->numberBetween(20, 9999)
        ];
    }
}
