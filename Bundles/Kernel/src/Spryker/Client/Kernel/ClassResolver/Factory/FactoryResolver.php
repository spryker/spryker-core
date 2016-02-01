<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Client\Kernel\ClassResolver\Factory;

use Spryker\Client\Kernel\ClassResolver\AbstractClassResolver;

class FactoryResolver extends AbstractClassResolver
{

    const CLASS_NAME_PATTERN = '\\%1$s\\Client\\%2$s%3$s\\%2$sFactory';

    /**
     * @param object|string $callerClass
     *
     * @throws FactoryNotFoundException
     *
     * @return \Spryker\Client\Kernel\AbstractFactory
     */
    public function resolve($callerClass)
    {
        $this->setCallerClass($callerClass);
        if ($this->canResolve()) {
            return $this->getResolvedClassInstance();
        }

        throw new FactoryNotFoundException($this->getClassInfo());
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
