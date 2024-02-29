<?php

declare(strict_types=1);

namespace App\Doctrine\DBAL\Type;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;

abstract class EnumType extends Type
{
    /**
     * @return class-string
     */
    abstract public static function getEnum(): string;

    public function getSQLDeclaration(array $column, AbstractPlatform $platform)
    {
        $enum = static::getEnum();
        $reflectionClass = new \ReflectionClass($enum);
        /** @var array<string,string|int> */
        $constants = $reflectionClass->getConstants();
        $constants = array_map(fn ($constant) => "'$constant'", $constants);
        return sprintf('ENUM(%s)', implode(', ', $constants));
    }
    public function requiresSQLCommentHint(AbstractPlatform $platform)
    {
        return true;
    }
}