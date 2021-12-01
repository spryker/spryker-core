<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\Kernel\ClassResolver\Factory;

use Spryker\Service\Kernel\ClassResolver\AbstractClassResolver;

class FactoryResolver extends AbstractClassResolver
{
    /**
     * @var string
     */
    protected const RESOLVABLE_TYPE = 'ServiceFactory';

    /**
     * @param object|string $callerClass
     *
     * @throws \Spryker\Service\Kernel\ClassResolver\Factory\FactoryNotFoundException
     *
     * @return \Spryker\Service\Kernel\AbstractServiceFactory
     */
    public function resolve($callerClass)
    {
        /** @var \Spryker\Service\Kernel\AbstractServiceFactory|null $resolved */
        $resolved = $this->doResolve($callerClass);
        if ($resolved === null) {
            throw new FactoryNotFoundException($this->getClassInfo());
        }

        return $resolved;
    }
}
