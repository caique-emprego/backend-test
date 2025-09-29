<?php

namespace App\UseCases\Account;

use Throwable;
use App\UseCases\BaseUseCase;
use App\Repositories\User\Find;
use App\Repositories\Account\Create;
use App\Exceptions\InternalErrorException;
use App\Integrations\Banking\Account\Create as BankingCreate;

class Register extends BaseUseCase
{
    /**
     * Id do usuário
     *
     * @var string
     */
    protected string $userId;

    /**
     * Id da empresa
     *
     * @var string
     */
    protected string $companyId;

    /**
     * Usuário
     *
     * @var array
     */
    protected array $user;

    /**
     * Conta
     *
     * @var array
     */
    protected array $account;

    public function __construct(string $userId, string $companyId)
    {
        $this->userId    = $userId;
        $this->companyId = $companyId;
    }

    /**
     * Encontra o usuário
     *
     * @return void
     */
    protected function findUser(): void
    {
        $user = (new Find($this->userId, $this->companyId))->handle();
        if (is_null($user)) {
            throw new InternalErrorException(
                'USER_NOT_FOUND',
                146001001
            );
        }

        $this->user = $user;
    }

    /**
     * Cria a conta
     *
     * @return void
     */
    protected function register(): void
    {
        /**
         * Rota 9: A implementaçao da integraçao com a BaaS deveria ser desaclopada de forma
         * que fosse possivel trocar de fornecedor sem impactar o código e a camada de UseCase
         * nao deveria ter que conhecer detalhes da integraçao.
         * Nao foi feita uma interface para a integraçao com a BaaS.
         */

        $this->account = (new BankingCreate(
            $this->user['name'],
            $this->user['document_number'],
            $this->user['email']
        ))->handle();
    }

    /**
     * Registra no banco de dados
     *
     * @return void
     */
    protected function store(): void
    {
        (new Create($this->userId, $this->account['data']['id']))->handle();
    }

    /**
     * Cria a conta
     */
    public function handle(): void
    {
        try {
            $this->findUser();
            $this->register();
            $this->store();
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
