<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerEngine\Zed\Kernel\ClassResolver\Facade;

use SprykerEngine\Zed\Kernel\Business\AbstractFacade;
use SprykerEngine\Zed\Kernel\ClassResolver\AbstractClassResolver;

class FacadeResolver extends AbstractClassResolver
{

    const CLASS_NAME_PATTERN = '\\%1$s\\%2$s\\%3$s%4$s\\Business\\%3$sFacade';

    /**
     * @param object|string $callerClass
     *
     * @throws FacadeNotFoundException
     *
     * @return AbstractFacade
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
            self::KEY_APPLICATION,
            self::KEY_BUNDLE,
            self::KEY_STORE
        );
    }

}
