<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Kernel\ClassResolver\Business;

use Spryker\Zed\Kernel\ClassResolver\AbstractClassResolver;

class BusinessFactoryResolver extends AbstractClassResolver
{
    protected const RESOLVABLE_TYPE = 'ZedFactoryBusiness';

    /**
     * @param object|string $callerClass
     *
     * @throws \Spryker\Zed\Kernel\ClassResolver\Business\BusinessFactoryNotFoundException
     *
     * @return \Spryker\Zed\Kernel\Business\AbstractBusinessFactory
     */
    public function resolve($callerClass)
    {
        $resolved = parent::doResolve($callerClass);

        if ($resolved !== null) {
            return $resolved;
        }

        throw new BusinessFactoryNotFoundException($this->getClassInfo());
    }
}
