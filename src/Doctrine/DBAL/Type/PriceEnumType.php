<?php

declare(strict_types=1);

namespace App\Doctrine\DBAL\Type;

use App\Entity\Enum\PriceTypeEnum;

class PriceEnumType extends EnumType
{
    final public const NAME = 'price_type_enum';

    public static function getEnum(): string
    {
        return PriceTypeEnum::class;
    }

    public function getName(): string
    {
        return self::NAME;
    }
}