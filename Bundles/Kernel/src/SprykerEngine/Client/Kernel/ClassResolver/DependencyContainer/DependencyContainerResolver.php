<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Client\Kernel\ClassResolver\DependencyContainer;

use Spryker\Client\Kernel\ClassResolver\AbstractClassResolver;
use Spryker\Client\Kernel\DependencyContainer\DependencyContainerInterface;

class DependencyContainerResolver extends AbstractClassResolver
{

    const CLASS_NAME_PATTERN = '\\%1$s\\Client\\%2$s%3$s\\%2$sDependencyContainer';

    /**
     * @param object|string $callerClass
     *
     * @throws DependencyContainerNotFoundException
     *
     * @return DependencyContainerInterface
     */
    public function resolve($callerClass)
    {
        $this->setCallerClass($callerClass);
        if ($this->canResolve()) {
            return $this->getResolvedClassInstance();
        }

        throw new DependencyContainerNotFoundException($this->getClassInfo());
    }

    /**
     * @return string
     */
    public function getClassPattern()
    {
        return sprintf(
            self::CLASS_NAME_PATTERN,
            self::KEY_NAMESPACE,
            self::KEY_BUNDLE,
            self::KEY_STORE
        );
    }

}
