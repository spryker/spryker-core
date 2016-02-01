<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Client\Kernel\ClassResolver\DependencyProvider;

use Spryker\Client\Kernel\ClassResolver\AbstractClassResolver;

class DependencyProviderResolver extends AbstractClassResolver
{

    const CLASS_NAME_PATTERN = '\\%1$s\\Client\\%2$s%3$s\\%2$sDependencyProvider';

    /**
     * @param object|string $callerClass
     *
     * @throws DependencyProviderNotFoundException
     *
     * @return \Spryker\Client\Kernel\AbstractDependencyProvider
     */
    public function resolve($callerClass)
    {
        $this->setCallerClass($callerClass);
        if ($this->canResolve()) {
            return $this->getResolvedClassInstance();
        }

        throw new DependencyProviderNotFoundException($this->getClassInfo());
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
