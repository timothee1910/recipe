<?php

namespace App\Entity\Enum;

use Overblog\GraphQLBundle\Annotation as GQL;

#[GQL\Enum(name: 'PriceTypeEnum')]
class PriceTypeEnum
{
    final public const EXCL_TAX_BASE = 'EXCL_TAX_BASE';
    final public const EXCL_TAX_TOTAL = 'EXCL_TAX_TOTAL';
    final public const INCL_TAX_BASE = 'INCL_TAX_BASE';
    final public const INCL_TAX_TOTAL = 'INCL_TAX_TOTAL';
    final public const VAT_BASE_AMOUNT = 'VAT_BASE_AMOUNT';

    public string $value;
}
