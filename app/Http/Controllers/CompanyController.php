<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\UseCases\Company\Show;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Responses\DefaultResponse;
use App\Http\Requests\Company\UpdateRequest;
use App\Http\Resources\Company\ShowResource;
use App\Http\Resources\Company\UpdateResource;
use App\Domains\Company\Update as UpdateDomain;
use App\Repositories\Company\Update as CompanyUpdate;

class CompanyController extends Controller
{
    /**
     * Endpoint de dados de empresa
     *
     * GET api/company
     *
     * @return JsonResponse
     */
    public function show(): JsonResponse
    {
        /**
        * Rota 3: Aqui o dominio nao foi utilizado, mostrando falta de consistência na arquitetura do projeto.
        * Também temos que ter uma atençao ao fato do usuário nao necessariamente ter uma empresa associada
        * e o código nao tratar essa exceção, o que retornaria um erro 500 para o usuário final
        * já que o id da Classe Show é um string nao nullable
        */
        $response = (new Show(Auth::user()->company_id))->handle();

        return $this->response(
            new DefaultResponse(
                new ShowResource($response)
            )
        );
    }

    /**
     * Endpoint de modificação de empresa
     *
     * PATCH api/company
     *
     * @return JsonResponse
     */
    public function update(UpdateRequest $request): JsonResponse
    {
        $params = $request->validated();

        $dominio = (new UpdateDomain(
            Auth::user()->company_id,
            $request->name,
        ))->handle();
        (new CompanyUpdate($dominio))->handle();

        /**
         * Rota 4: Trocar nome de variavel para o inglês para manter o padrao do projeto
         * Aqui também é chamado a model Company diretamente, quebrando a arquitetura do projeto
         * Além de ser feito um first() desnecessário, já que o find() já retorna um único registro
         */
        $resposta = Company::find(Auth::user()->company_id)->first()->toArray();

        return $this->response(
            new DefaultResponse(
                // Rota 4: as API Resources do Laravel aceitam models, nao precisa converter para array
                new UpdateResource($resposta)
            )
        );
    }
}
