<?php

namespace App\UseCases\User;

use Throwable;
use App\UseCases\BaseUseCase;
use App\Repositories\User\Retrieve;
use Illuminate\Pagination\LengthAwarePaginator;

class Index extends BaseUseCase
{
    /**
     * @var string
     */
    protected string $companyId;

    /**
     * Name
     *
     * @var string|null
     */
    protected ?string $name;

    /**
     * Email
     *
     * @var string|null
     */
    protected ?string $email;

    /**
     * Status
     *
     * @var string|null
     */
    protected ?string $status;

    /**
     * Usuários
     *
     * @var LengthAwarePaginator
     */
    /**
     * Rota 5: A classe LengthAwarePaginator é utilizada para customizar a paginaçao, para esse caso pode-se usar o metódo paginate() do Eloquent que já retorna uma instancia dessa classe
     */
    protected LengthAwarePaginator $users;

    public function __construct(string $companyId, ?string $name, ?string $email, ?string $status)
    {
        $this->companyId = $companyId;
        $this->name      = $name;
        $this->email     = $email;
        $this->status    = $status;
    }

    /**
     * Encontra os usuários
     *
     * @return void
     */
    protected function retrieve(): void
    {
        $this->users = (new Retrieve(
            $this->companyId,
            $this->name,
            $this->email,
            $this->status
        ))->handle();
    }

    /**
     * Retorna lista de usuários
     */
    public function handle(): LengthAwarePaginator
    {
        try {
            $this->retrieve();
        } catch (Throwable $th) {
            $this->defaultErrorHandling(
                $th,
                [
                    'companyId' => $this->companyId,
                    'name'      => $this->name,
                    'email'     => $this->email,
                    'status'    => $this->status,
                ]
            );
        }

        return $this->users;
    }
}
