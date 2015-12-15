<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Kernel\ClassResolver\Config;

use Spryker\Zed\Kernel\AbstractBundleConfig;
use Spryker\Zed\Kernel\ClassResolver\AbstractClassResolver;
use Spryker\Zed\Kernel\ClassResolver\DependencyContainer\BundleConfigNotFoundException;

class BundleConfigResolver extends AbstractClassResolver
{

    const CLASS_NAME_PATTERN = '\\%1$s\\%2$s\\%3$s%4$s\\%3$sConfig';

    /**
     * @param object|string $callerClass
     *
     * @throws BundleConfigNotFoundException
     *
     * @return AbstractBundleConfig
     */
    public function resolve($callerClass)
    {
        $this->setCallerClass($callerClass);
        if ($this->canResolve()) {
            return $this->getResolvedClassInstance();
        }

        throw new BundleConfigNotFoundException($this->getClassInfo());
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
