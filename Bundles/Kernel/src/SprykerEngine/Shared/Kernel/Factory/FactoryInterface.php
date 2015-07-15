<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerEngine\Shared\Kernel\Factory;

/**
 * Interface FactoryInterface
 */
interface FactoryInterface
{

    /**
     * @param string $class
     *
     * @return object
     */
    public function create($class);

    /**
     * @param string $class
     *
     * @return bool
     */
    public function exists($class);

}
