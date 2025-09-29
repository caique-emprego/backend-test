<?php

namespace App\Repositories\User;

use App\Models\User;
use App\Repositories\BaseRepository;
use Illuminate\Pagination\LengthAwarePaginator;

class Retrieve extends BaseRepository
{
    /**
     * Id da empresa
     *
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
     * Setar a model do usuário
     *
     * @return void
     */
    public function setModel(): void
    {
        $this->model = User::class;
    }

    public function __construct(string $companyId, ?string $name, ?string $email, ?string $status)
    {
        $this->companyId = $companyId;
        $this->name      = $name;
        $this->email     = $email;
        $this->status    = $status;

        parent::__construct();
    }

    /**
     * Left join com accounts
     *
     * @return void
     */
    protected function leftJoinAccount(): void
    {
        $this->builder->leftJoin(
            'accounts',
            'accounts.user_id',
            '=',
            'users.id'
        );
    }

    /**
     * Lista de usuários (Paginado)
     *
     * @return LengthAwarePaginator
     */
    public function handle(): LengthAwarePaginator
    {
        /**
         * Rota 5: A lógica de filtro deveria estar em um UseCase, e nao no repositório
         * O repositório deve ser responsável apenas por interagir com a fonte de dados
         * e retornar os dados solicitados.
         * Esse metódo de filtro com whereRaw é vulnerável a SQL Injection, utilizar bindings ou Eloquent.
         * O método também nao deveria retornar um LengthAwarePaginator, mas sim uma coleção de models ou DTOs
         * e a paginação deveria ser feita em um UseCase ou serviço específico para isso.
         * Além disso, o uso de strings para status é propenso a erros de digitação e inconsistências. Deveria ser utilizado um enum para definir os possíveis status.
         * A lógica de paginação deveria ser parametrizada, permitindo ao chamador definir o número de itens por página e a página atual
         * ao invés de ser fixo dentro do repositório.
         * A chamada da propriedade builder diretamente pode expor a lógica interna do repositório, quebrando o encapsulamento.
         * Um usuário pode ter mais de uma conta, o que pode resultar em múltiplas linhas para o mesmo usuário na listagem.
         */


        $this->leftJoinAccount();

        if ($this->name) {
            $this->builder->whereRaw("name LIKE '%" . $this->name . "%'");
        }

        if ($this->email) {
            $this->builder->whereRaw("email LIKE '%" . $this->email . "%'");
        }

        if ($this->status) {
            // Utilizar enum para status
            if ($this->status === 'INACTIVE') {
                $this->builder->whereRaw('accounts.id IS NULL');
            } else {
                $this->builder->whereRaw('accounts.status = "' . $this->status . '"');
            }
        }

        $this->builder->where('company_id', $this->companyId)
            ->orderBy('name');

        return $this->paginate(['users.*']);
    }
}
