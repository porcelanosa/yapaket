<?php

declare(strict_types = 1);

namespace App\Helpers;

use MoonShine\Support\Enums\Color;
class ColorsHelper
{
    public static function getColorFromString(string $text): Color {
        $cases = Color::cases();
        $caseCount = count($cases);

        // Вычисляем хеш строки и преобразуем его в большое целое число
        // Используем hexdec и substr для получения числа из хеша
        $hash = md5($text);
        // Берем первые 10 символов хеша (достаточно для получения числа)
        $hashPart = substr($hash, 0, 10);
        // Преобразуем шестнадцатеричную строку в десятичное число
        // gmp_init и gmp_strval используются для работы с большими числами,
        // если hexdec не справится с переполнением на 32-битных системах.
        // Для большинства случаев hexdec($hashPart) будет достаточно.
        if (extension_loaded('gmp')) {
            $hashNumber = gmp_strval(gmp_init($hashPart, 16));
            $hashNumber = (int)($hashNumber % $caseCount);
        } else {
            // Простая альтернатива, если GMP недоступно, но может быть менее точной для очень больших чисел
            // На практике для распределения обычно достаточно
            $hashNumber = hexdec($hashPart) % $caseCount;
        }

        // Используем остаток от деления для получения индекса
        $index = abs($hashNumber) % $caseCount;

        // Возвращаем соответствующий цвет
        return $cases[$index];

    }
}