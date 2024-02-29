<?php

namespace App\Service;

use App\Entity\Item;
use App\PriceGenerator\GeneratorPriceInterface;
use Doctrine\ORM\EntityManagerInterface;
use Exception;

class PriceService
{
    /**
     * @var GeneratorPriceInterface[]
     */
    public array $generators;

    /**
     * @param GeneratorPriceInterface[] $generators
     */
    public function __construct($generators, private readonly EntityManagerInterface $em)
    {
        $this->generators = $generators instanceof \Traversable ? iterator_to_array($generators) : $generators;
        usort($this->generators, fn ($a, $b) => $this->getOrder($a::class) - $this->getOrder($b::class));
    }

    /**
     * @param Item[] $item
     */
    public function generatePricesForItems(array $items): void
    {
      
        foreach ($items as $item) {
            $this->generatePricesForItem($item);
        }
    }

    public function generatePricesForItem(Item $item): void
    {
        $prices = [];
        foreach ($this->generators as $generator) {
            $generator->generatePrice($item, $prices);
        }
    }

    /**
     * @param class-string             $class
     * @param array<class-string,bool> $trace
     */
    public function getOrder(string $class, array $trace = []): int
    {
        if (array_key_exists($class, $trace)) {
            throw new Exception(sprintf("error in price generator dependencies. circular dependencies for %s.\ntrace: \n%s", $class, join("\n", array_keys($trace))));
        }
        $trace[$class] = true;

        if (!method_exists($class, 'getDependencies')) {
            throw new Exception('No method getDependencies');
        }

        /** @var callable(): array<class-string> */
        $callable = [$class, 'getDependencies'];

        /** @var array<class-string> */
        $dependencies = call_user_func($callable);

        return $this->getMaxOrder($dependencies, $trace) + 1;
    }

    /**
     * @param string[]                 $dependencies
     * @param array<class-string,bool> $trace
     *
     * @psalm-param array<class-string> $dependencies
     */
    public function getMaxOrder(array $dependencies, array $trace): int
    {
        $max = 0;
        foreach ($dependencies as $dependency) {
            $order = $this->getOrder($dependency, $trace);
            if ($max < $order) {
                $max = $order;
            }
        }

        return $max;
    }
}
