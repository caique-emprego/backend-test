<?php

namespace App\UseCases\Account;

use Throwable;
use App\UseCases\BaseUseCase;
use App\Repositories\Account\UpdateStatus as RepositoryUpdateStatus;
use App\Integrations\Banking\Account\UpdateStatus as IntegrationUpdateStatus;

class Active extends BaseUseCase
{
    /**
     * Id do usuário
     *
     * @var string
     */
    protected string $userId;

    /**
     * Conta
     *
     * @var array
     */
    protected array $account;

    public function __construct(string $userId)
    {
        $this->userId = $userId;
    }

    /**
     * Atualiza no banco de dados
     *
     * @return void
     */
    protected function updateDatabase(): void
    {
        (new RepositoryUpdateStatus($this->userId, 'ACTIVE'))->handle();
    }

    /**
     * Atualiza a conta
     *
     * @return void
     */
    protected function updateStatus(): void
    {
        $this->account = (new IntegrationUpdateStatus($this->userId, 'active'))->handle();
    }

    /**
     * Ativa a conta
     */
    public function handle(): void
    {
        try {
            /**
             * Rota 11: Aqui sao chamadas duas funçoes: Uma faz uma atualizaçao no banco de dados e outra faz uma chamada externa.
             * O ideal seria que a chamada externa fosse feita primeiro, e caso ela falhe, a atualizaçao no banco de dados nao deveria ser feita.
             * Além de que pelo nome das funçoes, nao fica claro que uma faz uma chamada externa e a outra faz uma atualizaçao no banco de dados.
             * */

            $this->updateDatabase();
            $this->updateStatus();
        } catch (Throwable $th) {
            $this->defaultErrorHandling(
                $th,
                [
                    'userId' => $this->userId,
                ]
            );
        }
    }
}
