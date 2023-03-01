<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Pessoa;

class PessoaFactory extends Factory
{
    public function definition()
    {
        return [
            'nome' => $this->faker->name(),
            'cpf' => '01368064302',
            'email' => $this->faker->companyEmail(),
            'data_nasc' => $this->faker->date(),
            'nacionalidade' => $this->faker->word(),
        ];
    }
}
