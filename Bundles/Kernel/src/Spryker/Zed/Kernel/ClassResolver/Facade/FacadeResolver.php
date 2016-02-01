<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Kernel\ClassResolver\Facade;

use Spryker\Zed\Kernel\Business\AbstractFacade;
use Spryker\Zed\Kernel\ClassResolver\AbstractClassResolver;

class FacadeResolver extends AbstractClassResolver
{

    const CLASS_NAME_PATTERN = '\\%1$s\\Zed\\%2$s%3$s\\Business\\%2$sFacade';

    /**
     * @param object|string $callerClass
     *
     * @throws FacadeNotFoundException
     *
     * @return \Spryker\Zed\Kernel\Business\AbstractFacade
     */
    public function resolve($callerClass)
    {
        $this->setCallerClass($callerClass);
        if ($this->canResolve()) {
            return $this->getResolvedClassInstance();
        }

        throw new FacadeNotFoundException($this->getClassInfo());
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
