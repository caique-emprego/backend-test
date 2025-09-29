<?php

namespace App\UseCases\User;

use Throwable;
use App\UseCases\BaseUseCase;
use App\Domains\User\Create as CreateUserDomain;
use App\Repositories\Token\Create as CreateToken;
use App\UseCases\Params\User\CreateFirstUserParams;

// Rota 1: aqui todos as Classses sao chamadas com Alias diferentes, porque apenas nao alterar o nome da própria classe pelo nome do Alias?
use App\Domains\Company\Create as CreateCompanyDomain;
use App\Repositories\User\Create as CreateUserRepository;
use App\Repositories\Company\Create as CreateCompanyRepository;

class CreateFirstUser extends BaseUseCase
{
    /**
     * @var CreateFirstUserParams
     */

    // Rota 1: trocar por request (RegisterRequest) ou Entity.
    protected CreateFirstUserParams $params;

    /**
     * Token de acesso
     *
     * @var string
     */
    protected string $token;

    /**
     * Empresa
     *
     * @var array
     */
    protected array $company;

    /**
     * Usuário
     *
     * @var array
     */
    protected array $user;

    public function __construct(
        CreateFirstUserParams $params
    ) {
        $this->params = $params;
    }

    /**
     * Valida a empresa
     *
     * @return CreateCompanyDomain
     */
    protected function validateCompany(): CreateCompanyDomain
    {
        return (new CreateCompanyDomain(
            $this->params->companyName,
            $this->params->companyDocumentNumber
        ))->handle();
    }

    /**
     * Cria a empresa
     *
     * @param CreateCompanyDomain $domain
     *
     * @return void
     */
    protected function createCompany(CreateCompanyDomain $domain): void
    {
        $this->company = (new CreateCompanyRepository($domain))->handle();
    }

    /**
     * Valida o usuário
     *
     * @return CreateUserDomain
     */
    protected function validateUser(): CreateUserDomain
    {
        return (new CreateUserDomain(
            $this->company['id'],
            $this->params->userName,
            $this->params->userDocumentNumber,
            $this->params->email,
            $this->params->password,
            // Rota 1: Utilizar Enum
            'MANAGER'
        ))->handle();
    }

    /**
     * Cria o usuário
     *
     * @param CreateUserDomain $domain
     *
     * @return void
     */
    protected function createUser(CreateUserDomain $domain): void
    {
        $this->user = (new CreateUserRepository($domain))->handle();
    }

    /**
     * Criação de token de acesso
     *
     * @return void
     */
    protected function createToken(): void
    {
        $this->token = (new CreateToken($this->user['id']))->handle();
    }

    /**
     * Cria um usuário MANAGER e a empresa
     */
    public function handle()
    {
        try {
            $companyDomain = $this->validateCompany();
            // Rota 1: é feito a criaçao da empresa antes de validar o usuário.
            $this->createCompany($companyDomain);
            $userDomain = $this->validateUser();
            $this->createUser($userDomain);
            $this->createToken();
        } catch (Throwable $th) {
            $this->defaultErrorHandling(
                $th,
                [
                    'params' => $this->params->toArray(),
                ]
            );
        }

        return [
            'user'    => $this->user,
            'company' => $this->company,
            'token'   => $this->token,
        ];
    }
}
