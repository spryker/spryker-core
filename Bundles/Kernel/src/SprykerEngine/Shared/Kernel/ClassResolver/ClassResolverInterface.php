<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerEngine\Shared\Kernel\ClassResolver;

interface ClassResolverInterface
{

    /**
     * @param string $classNamePattern
     * @param string $bundle
     *
     * @return bool
     */
    public function canResolve($classNamePattern, $bundle);

    /**
     * @param string $classNamePattern
     * @param string $bundle
     * @param array  $arguments
     *
     * @return object
     */
    public function resolve($classNamePattern, $bundle, array $arguments = []);

}
