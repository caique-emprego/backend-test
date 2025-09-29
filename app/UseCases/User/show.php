<?php

namespace App\UseCases\User;

use Throwable;
use App\UseCases\BaseUseCase;
use App\Repositories\User\Find;

class show extends BaseUseCase
{
    /**
     * Rota 6: O nome da classe e do arquivo devem ser PascalCase.
     * Além disso as propriedades e variáveis devem ter nomes descritivos.
     */

    /**
     * Id do usuário
     *
     * @var string
     */
    protected string $a;

    /**
     * Id da empresa
     *
     * @var string
     */
    protected string $b;

    /**
     * Usuário
     *
     * @var array|null
     */
    protected ?array $c;

    public function __construct(string $a, string $b)
    {
        $this->a = $a;
        $this->b = $b;
    }

    /**
     * Encontra o usuário
     *
     * @return void
     */
    protected function find(): void
    {
        $this->c = (new Find($this->a, $this->b))->handle();
    }

    /**
     * Retorna usuário, se encontrado
     */
    public function handle(): ?array
    {
        try {
            $this->find();
        } catch (Throwable $th) {
            $this->defaultErrorHandling(
                $th,
                [
                    'a' => $this->a,
                    'b' => $this->b,
                ]
            );
        }

        return $this->c;
    }
}
