<?php
namespace App\Service;

use App\Entity\Enum\PriceTypeEnum;
use App\Entity\Item;
use App\Entity\Price;
use Doctrine\ORM\EntityManagerInterface;

class ItemService
{
    public function __construct(private readonly EntityManagerInterface $em)
    {
    }

    public function createPrice(Item $item, PriceTypeEnum|string $type, ?int $amount, string $calcul, array $variables): Price
    {
        $price = (new Price())
            ->setItem($item)
            ->setType(!is_string($type) ? $type->value : $type)
            ->setAmount(null != $amount ? $amount : $item->getAmount())
            ->setCalcul($calcul)
            ->setVariables($variables)
        ;
        
        $this->em->persist($price);
        $this->em->flush();

        return $price;
    }
}