<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Pessoa;

class PessoaTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function setUp(): void
    {
        parent::setUp();

        $this->artisan('db:seed');
    }

    private function insertTestPessoa()
    {
        return Pessoa::factory()->create();
    }

    public function testIndexWillReturnListPessoas()
    {
        $this->insertTestPessoa();

        $this->json('GET', 'api/pessoa')
            ->assertOk()
            ->assertJsonStructure([
                'data' => [
                    [
                        'nome',
                        'cpf',
                        'email',
                        'data_nasc',
                        'nacionalidade',
                    ]
                ]
            ]);
    }

    public function testShowWillReturnAnPessoa()
    {
        $pessoa = $this->insertTestPessoa();

        $this->json('GET', 'api/pessoa/' . $pessoa->id)
            ->assertOk()
            ->assertJsonFragment(['id' => $pessoa->id])
            ->assertJsonStructure(
                [
                    'nome',
                    'cpf',
                    'email',
                    'data_nasc',
                    'nacionalidade',
                ]
            );
    }

    public function testStoreWillCreatePessoa()
    {
        $pessoa = Pessoa::factory()->raw();

        $this->json('POST', 'api/pessoa', $pessoa)
            ->assertStatus(201);

        $this->assertDatabaseHas(
            Pessoa::class, [
                "nome" => $pessoa['nome'],
                "nacionalidade" => $pessoa['nacionalidade'],
            ]
        );
    }

    public function testStoreWillReturnErrorWhenFormRequestValidationFails()
    {
        $pessoa = Pessoa::factory([
            'nome' => null,
        ])->raw();

        $this->json('POST', 'api/pessoa', $pessoa)->assertStatus(422);
    }

    public function testUpdateWillEditPessoa()
    {
        $pessoa = $this->insertTestPessoa();

        $pessoaEdit = [
            'nome' => $this->faker->name(),
        ];

        $this->json('PUT', 'api/pessoa/' . $pessoa->id, $pessoaEdit)
            ->assertStatus(201);

        $this->assertDatabaseHas(
            Pessoa::class, [
                "nome" => $pessoaEdit['nome'],
            ]
        );
    }

    public function testDestroyWillHardDeletePessoa()
    {
        $pessoa = $this->insertTestPessoa();

        $this->json('DELETE', 'api/pessoa/' . $pessoa->id)
            ->assertOk()
            ->assertStatus(200);
    }
}
