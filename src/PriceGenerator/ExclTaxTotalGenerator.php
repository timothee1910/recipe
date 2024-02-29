<?php

namespace App\PriceGenerator;

use App\Entity\Enum\PriceTypeEnum;
use App\Entity\Item;
use App\Entity\Price;
use App\PriceGenerator\GeneratorPriceInterface;
use App\Service\PriceService;
use TypeError;

class ExclTaxTotalGenerator
{
    public function __construct(
        private readonly PriceService $priceService,
    ) {
    }

    public function generatePrice(Item $item, array $prices): Price
    {

        $exclTaxBaseGenerator = $prices[ExclTaxBaseGenerator::getName()];
        dump('dede');
        if (is_array($exclTaxBaseGenerator)) {
            throw new TypeError('$prices wrong structure');
        }
        dump($exclTaxBaseGenerator->getAmount() + 2);
        return $this->priceService->createPrice(
            $item,
            PriceTypeEnum::EXCL_TAX_TOTAL,
            $exclTaxBaseGenerator->getAmount() + 2,
            static::getCalcul(),
            [
                '$base' => [$exclTaxBaseGenerator->getAmount(), 'base item'],
                '$commision' => [2]
            ]
        );
    }

    public static function getName(): string
    {
        return PriceTypeEnum::EXCL_TAX_TOTAL;
    }

    public static function getCalcul(): string
    {
        return 'base amount with commision';
    }

    public static function getDependencies(): array
    {
        return [ExclTaxBaseGenerator::class];
    }
}
