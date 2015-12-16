<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Yves\Kernel\ClassResolver\DependencyContainer;

use Spryker\Yves\Kernel\AbstractDependencyContainer;
use Spryker\Yves\Kernel\ClassResolver\AbstractClassResolver;

class DependencyContainerResolver extends AbstractClassResolver
{

    const CLASS_NAME_PATTERN = '\\%1$s\\Yves\\%2$s%3$s\\%2$sDependencyContainer';

    /**
     * @param object|string $callerClass
     *
     * @throws DependencyContainerNotFoundException
     *
     * @return AbstractDependencyContainer
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
