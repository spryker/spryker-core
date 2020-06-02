<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\Kernel\ClassResolver\Factory;

use Spryker\Yves\Kernel\ClassResolver\AbstractClassResolver;

class FactoryResolver extends AbstractClassResolver
{
    protected const RESOLVABLE_TYPE = 'YvesFactory';

    /**
     * @param object|string $callerClass
     *
     * @throws \Spryker\Yves\Kernel\ClassResolver\Factory\FactoryNotFoundException
     *
     * @return \Spryker\Yves\Kernel\AbstractFactory
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
