<?php

namespace App\Traits;

trait Instancer
{
    /**
     * Método para criar uma nova instância de uma classe
     *
     * @param string $className
     * @param mixed ...$parameters
     *
     * @return mixed
     */

    // Rota 1: O método é desnecessário, uma vez que o PHP já faz isso nativamente com o operador "new".
    public function instance(string $className, ...$parameters): mixed
    {
        return new $className(...$parameters);
    }
}
