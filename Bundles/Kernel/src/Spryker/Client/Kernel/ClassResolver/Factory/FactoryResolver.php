<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Kernel\ClassResolver\Factory;

use Spryker\Client\Kernel\ClassResolver\AbstractClassResolver;

class FactoryResolver extends AbstractClassResolver
{
    protected const RESOLVABLE_TYPE = 'ClientFactory';

    /**
     * @param object|string $callerClass
     *
     * @throws \Spryker\Client\Kernel\ClassResolver\Factory\FactoryNotFoundException
     *
     * @return \Spryker\Client\Kernel\AbstractFactory
     */
    public function resolve($callerClass)
    {
        $resolved = $this->doResolve($callerClass);

        if ($resolved !== null) {
            return $resolved;
        }

        throw new FactoryNotFoundException($this->getClassInfo());
    }
}
