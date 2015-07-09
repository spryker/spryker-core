<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Unit\SprykerEngine\Shared\Kernel\Fixtures;

use SprykerEngine\Shared\Kernel\AbstractFactory;

class MissingPropertyKernelFactory extends AbstractFactory
{

    /**
     * @param string $class
     *
     * @throws \Exception
     *
     * @return object
     */
    public function create($class)
    {
    }

}
