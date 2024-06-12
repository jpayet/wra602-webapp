<?php

namespace App\Service;

use Faker\Factory;

class PdfFileNameGeneratorService
{
    private $faker;

    public function __construct()
    {
        $this->faker = Factory::create();
    }

    public function generateFileName(): string
    {
        $word1 = $this->faker->word;
        $word2 = $this->faker->word;

        return sprintf('%s-%s.pdf', $word1, $word2);
    }
}
