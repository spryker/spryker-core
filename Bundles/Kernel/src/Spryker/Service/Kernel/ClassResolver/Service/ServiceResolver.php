<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\Kernel\ClassResolver\Service;

use Spryker\Service\Kernel\ClassResolver\AbstractClassResolver;

class ServiceResolver extends AbstractClassResolver
{
    const CLASS_NAME_PATTERN = '\\%1$s\\Service\\%2$s%3$s\\%2$sService';

    /**
     * @param object|string $callerClass
     *
     * @throws \Spryker\Service\Kernel\ClassResolver\Service\ServiceNotFoundException
     *
     * @return \Spryker\Service\Kernel\AbstractService
     */
    public function resolve($callerClass)
    {
        $this->setCallerClass($callerClass);

        if ($this->canResolve()) {
            /** @var \Spryker\Service\Kernel\AbstractService $class */
            $class = $this->getResolvedClassInstance();

            return $class;
        }

        throw new ServiceNotFoundException($this->getClassInfo());
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
