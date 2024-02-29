<?php

namespace App\Service;

use Exception;

class GeneratorService
{
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
