<?php

namespace App\PriceGenerator;

use App\Entity\Item;
use App\Entity\Price;

interface GeneratorPriceInterface
{
    /**
     * @param array<Price> $prices 
     */
    public function generatePrice(Item $item, array &$prices): Price;

    public static function getName(): string;

    /** @return array<class-string> */
    public static function getDependencies(): array;

    public static function getCalcul(): string;
}