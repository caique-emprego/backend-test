<?php

namespace App\UseCases\User;

use Throwable;
use App\UseCases\BaseUseCase;
// Rota 2: Alias com sneak_case
use App\Repositories\Token\Create as create_token;

class Login extends BaseUseCase
{
    /**
     * @var string
     */
    protected string $id;

    /**
     * Token de acesso
     *
     * @var string
     */
    protected string $token;

    public function __construct(string $id)
    {
        $this->id = $id;
    }

    /**
     * Criação de token de acesso
     *
     * @return void
     */
    protected function createToken(): void
    {
        $this->token = (new create_token($this->id))->handle();
    }

    /**
     * Cria um usuário MANAGER e a empresa
     */
    public function handle()
    {
        try {
            $this->createToken();
        } catch (Throwable $th) {
            // Rota 2: Ao invés de utilizar try catch eu criaria um handler global para tratar erros internos como esse e só utilizaria throw em casos de erros esperados (Exceptions)
            $this->defaultErrorHandling(
                $th,
                [
                    'id' => $this->id,
                ]
            );
        }

        return [
            'token' => $this->token,
        ];
    }
}
