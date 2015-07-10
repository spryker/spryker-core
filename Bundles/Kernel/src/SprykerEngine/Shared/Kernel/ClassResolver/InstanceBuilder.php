<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerEngine\Shared\Kernel\ClassResolver;

class InstanceBuilder
{

    /**
     * @param string $className
     * @param array $arguments
     *
     * @return object
     */
    public function createInstance($className, array $arguments = [])
    {
        if (count($arguments) > 0) {
            $class = new \ReflectionClass($className);

            return $class->newInstanceArgs($arguments);
        } else {
            return new $className();
        }
    }

}
