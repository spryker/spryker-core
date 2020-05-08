<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Kernel\ClassResolver\Factory;

use Spryker\Shared\Kernel\ClassResolver\AbstractClassResolver;

/**
 * @method \Spryker\Shared\Kernel\AbstractSharedConfig getResolvedClassInstance()
 */
class SharedFactoryResolver extends AbstractClassResolver
{
    protected const RESOLVABLE_TYPE = 'SharedFactory';

    /**
     * @param object|string $callerClass
     *
     * @throws \Spryker\Shared\Kernel\ClassResolver\Factory\SharedFactoryNotFoundException
     *
     * @return \Spryker\Shared\Kernel\AbstractSharedFactory
     */
    public function resolve($callerClass)
    {
        $resolved = parent::doResolve($callerClass);

        if ($resolved !== null) {
            return $resolved;
        }

        throw new SharedFactoryNotFoundException($this->getClassInfo());
    }
}
