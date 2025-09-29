<?php

namespace App\Domains;

use App\Traits\Instancer;

abstract class BaseDomain
{
    use Instancer;

    /**
     * Obter uma propriedade da classe
     *
     * @param string $prop
     *
     * @return mixed
     */

    // Rota 1: Não deveria existir esse método.
    public function __get(string $prop): mixed
    {
        return $this->{$prop};
    }
}
