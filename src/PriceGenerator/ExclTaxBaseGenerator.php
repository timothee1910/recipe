<?php

namespace App\PriceGenerator;

use App\Entity\Enum\PriceTypeEnum;
use App\Entity\Item;
use App\Entity\Price;
use App\PriceGenerator\GeneratorPriceInterface;
use App\Service\ItemService;

/**
 * Prix du produit avec la remise pro hors taxe.
 */
class ExclTaxBaseGenerator implements GeneratorPriceInterface
{
    public function __construct(
        private readonly ItemService $itemService,
    ) {
    }

    public function generatePrice(Item $item, array &$prices): Price
    {
        return $this->itemService->createPrice(
            $item,
            PriceTypeEnum::EXCL_TAX_BASE,
            $item->getAmount(),
            static::getCalcul(),
            []);
    }

    public static function getName(): string
    {
        return PriceTypeEnum::EXCL_TAX_BASE;
    }

    public static function getCalcul(): string
    {
        return 'No calcul for this type excl_tax';
    }

    public static function getDependencies(): array
    {
        return [];
    }
}
