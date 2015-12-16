<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Kernel\ClassResolver\DependencyContainer;

use Spryker\Zed\Kernel\AbstractDependencyContainer;
use Spryker\Zed\Kernel\ClassResolver\AbstractClassResolver;

class DependencyContainerResolver extends AbstractClassResolver
{

    const CLASS_NAME_PATTERN = '\\%1$s\\Zed\\%2$s%4$s\\%3$s\\%2$sDependencyContainer';

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
            self::KEY_LAYER,
            self::KEY_STORE
        );
    }

    /**
     * @param string $namespace
     * @param string|null $store
     *
     * @return string
     */
    protected function buildClassName($namespace, $store = null)
    {
        $searchAndReplace = [
            self::KEY_NAMESPACE => $namespace,
            self::KEY_BUNDLE => $this->getClassInfo()->getBundle(),
            self::KEY_LAYER => $this->getClassInfo()->getLayer(),
            self::KEY_STORE => $store,
        ];

        $className = str_replace(
            array_keys($searchAndReplace),
            array_values($searchAndReplace),
            $this->getClassPattern()
        );

        return $className;
    }

}
