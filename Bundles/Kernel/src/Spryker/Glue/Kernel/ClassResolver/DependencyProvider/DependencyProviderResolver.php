<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\Kernel\ClassResolver\DependencyProvider;

use Spryker\Glue\Kernel\ClassResolver\AbstractClassResolver;

class DependencyProviderResolver extends AbstractClassResolver
{
    protected const RESOLVABLE_TYPE = 'GlueDependencyProvider';

    /**
     * @param object|string $callerClass
     *
     * @throws \Spryker\Glue\Kernel\ClassResolver\DependencyProvider\DependencyProviderNotFoundException
     *
     * @return \Spryker\Glue\Kernel\AbstractBundleDependencyProvider
     */
    public function resolve($callerClass)
    {
        $resolved = $this->doResolve($callerClass);

        if ($resolved !== null) {
            return $resolved;
        }

        throw new DependencyProviderNotFoundException($this->getClassInfo());
    }
}
