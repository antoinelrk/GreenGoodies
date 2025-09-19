<?php

namespace App\Traits;

use App\Enums\RolesEnum;

trait BaseEnum
{
    /**
     * Prefix for all enums namespace
     */
    protected const string NAMESPACE_PREFIX = 'App\\Enums\\';

    /**
     * Return the enum as an array.
     * [string $key => mixed $value]
     *
     * @return array
     */
    public static function asArray(): array
    {
        $array = [];

        foreach (static::cases() as $case) {
            $array[$case->name] = $case->value;
        }

        return $array;
    }

    /**
     * Return the enum as an array.
     * [int $key => mixed $value]
     *
     * @return array
     */
    public static function asArrayInt(): array
    {
        $array = [];

        foreach (static::cases() as $case) {
            $array[] = $case->value;
        }

        return $array;
    }

    /**
     * Return the enum's cases keys
     *
     * @return array
     */
    public static function caseKeys(): array
    {
        return array_keys(self::asArray());
    }

    /**
     * Check if a given case key exists
     *
     * @param string $key
     *
     * @return bool
     */
    public static function caseKeyExists(string $key): bool
    {
        return in_array($key, self::caseKeys());
    }

    /**
     * Return a case's value from its key
     *
     * @param string $key
     *
     * @return string|null
     */
    public static function getValue(string $key): ?string
    {
        return self::asArray()[$key] ?? null;
    }

    /**
     * Return the enum as an array with lowercase keys.
     * [string $key => mixed $value]
     *
     * @return array
     */
    public static function asLowercaseKeyArray(): array
    {
        return array_change_key_case(self::asArray());
    }

    /**
     * Get the enum case by its value.
     *
     * @param mixed $value
     *
     * @return RolesEnum|BaseEnum|null
     */
    public static function getEnumByValue(mixed $value): ?self
    {
        return array_find(static::cases(), fn ($case) => $case->value == $value);
    }

    /**
     * TODO: A déplacer dans un helper, ou dans une classe utilitaire.
     *
     * @param array $weights
     *
     * @return int|string|void
     */
    public static function weightedRandom(array $weights)
    {
        $total = array_sum($weights);
        $rand = mt_rand(1, $total);

        foreach ($weights as $item => $weight) {
            $rand -= $weight;
            if ($rand <= 0) {
                return $item;
            }
        }
    }
}
