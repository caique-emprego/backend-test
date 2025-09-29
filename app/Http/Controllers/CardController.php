<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\UseCases\Card\Register;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Http\Responses\DefaultResponse;
use App\Integrations\Banking\Card\Find;

class CardController extends Controller
{
    /**
     * Exibe dados de um cartão
     *
     * POST api/users/{id}/card
     *
     * @return JsonResponse
     */
    public function show(string $userId): JsonResponse
    {
        $response = (new Find($userId))->handle();

        return $this->response(
            new DefaultResponse($response['data'])
        );
    }

    /**
     * Ativa um cartão
     *
     * POST api/users/{id}/card
     *
     * @return JsonResponse
     */
    public function register(string $userId, Request $request): JsonResponse
    {
        /**
         * Rota 13: Sem o FormRequest nao temos conhecimento da regra de negócio.
         * Desta forma a regra de negócio fica "escondida" pro frontend gerenciar,
         * o que pode causar muitos problemas, além do mal uso da API.
         */
        $response = (new Register($userId, $request->pin, $request->card_id))->handle();

        return $this->response(
            new DefaultResponse($response['data'])
        );
    }
}
