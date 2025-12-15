<?php

namespace App\Utils;

class JsonHelper
{
    /**
     * Tenta decodificar uma string JSON.
     * Se for bem-sucedido e resultar em um array, retorna o array decodificado.
     * Caso contrário, retorna a string original (raw).
     *
     * @param string|null $jsonString A string que pode ser um JSON.
     * @return array|string|null O array decodificado, a string original, ou null.
     */
    public static function decodeOrReturnRaw(?string $jsonString): array|string|null
    {
        if (empty($jsonString)) return $jsonString;

        $decoded = json_decode($jsonString, true);
        $isSuccessful = json_last_error() === JSON_ERROR_NONE;
        $isArrayOrObject = is_array($decoded);

        if ($isSuccessful && $isArrayOrObject) return $decoded;
        return $jsonString;
    }
}
