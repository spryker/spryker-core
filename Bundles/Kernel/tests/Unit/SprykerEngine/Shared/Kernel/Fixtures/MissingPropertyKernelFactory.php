<?php

namespace Unit\SprykerEngine\Shared\Kernel\Fixtures;

use SprykerEngine\Shared\Kernel\AbstractFactory;

class MissingPropertyKernelFactory extends AbstractFactory
{

    /**
     * @param string $class
     *
     * @return object
     * @throws \Exception
     */
    public function create($class)
    {
    }
}
