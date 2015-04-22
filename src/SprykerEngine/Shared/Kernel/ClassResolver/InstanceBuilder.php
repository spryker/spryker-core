<?php

namespace SprykerEngine\Shared\Kernel\ClassResolver;

class InstanceBuilder
{

    /**
     * @TODO check performance here, maybe Reflection is to slow
     *
     * @param $className
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
