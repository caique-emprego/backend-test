<?php

namespace App\Domains\Company;

use App\Domains\BaseDomain;
use App\Exceptions\InternalErrorException;
use App\Repositories\Company\CanUseDocumentNumber;

// Rota 1: Nomenclatura ruim, CompanyDomain seria melhor.
class Create extends BaseDomain
{
    /**
     * Nome
     *
     * @var string
     */
    protected string $name;

    /**
     * CNPJ
     *
     * @var string
     */
    protected string $documentNumber;

    public function __construct(string $name, string $documentNumber)
    {
        $this->name           = $name;
        $this->documentNumber = $documentNumber;
    }

    /**
     * Documento de empresa deve ser único no sistema
     */
    protected function checkDocumentNumber()
    {

        // Rota 1: Throw dentro do dominio. Deveria ser feito na camada de aplicação (FormRequest talvez).
        if (!(new CanUseDocumentNumber($this->documentNumber))->handle()) {
            // Rota 1: Tratar mensagem e tipo de exceçao corretamente
            throw new InternalErrorException(
                'Não é possível adicionar o CNPJ informado',
                0
            );
        }
    }

    /**
     * Checa se é possível criar a empresa
     *
     * @return self
     */

    // Rota 1: Nao faz sentido um handle de um metódo "Create" dentro do domain fazer validaçao de dados.
    public function handle(): self
    {
        $this->checkDocumentNumber();

        return $this;
    }
}
