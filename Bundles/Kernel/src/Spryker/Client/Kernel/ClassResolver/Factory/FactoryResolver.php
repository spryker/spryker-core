<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Kernel\ClassResolver\Factory;

use Spryker\Client\Kernel\ClassResolver\AbstractClassResolver;

class FactoryResolver extends AbstractClassResolver
{
    public const CLASS_NAME_PATTERN = '\\%1$s\\Client\\%2$s%3$s\\%2$sFactory';

    /**
     * @param object|string $callerClass
     *
     * @throws \Spryker\Client\Kernel\ClassResolver\Factory\FactoryNotFoundException
     *
     * @return \Spryker\Client\Kernel\AbstractFactory
     */
    public function resolve($callerClass)
    {
        $this->setCallerClass($callerClass);
        if ($this->canResolve()) {
            /** @var \Spryker\Client\Kernel\AbstractFactory $class */
            $class = $this->getResolvedClassInstance();

            return $class;
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
