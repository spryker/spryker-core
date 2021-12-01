<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\Kernel\ClassResolver\DependencyProvider;

use Spryker\Service\Kernel\ClassResolver\AbstractClassResolver;

class DependencyProviderResolver extends AbstractClassResolver
{
    /**
     * @var string
     */
    protected const RESOLVABLE_TYPE = 'ServiceDependencyProvider';

    /**
     * @param object|string $callerClass
     *
     * @throws \Spryker\Service\Kernel\ClassResolver\DependencyProvider\DependencyProviderNotFoundException
     *
     * @return \Spryker\Service\Kernel\AbstractBundleDependencyProvider
     */
    public function resolve($callerClass)
    {
        /** @var \Spryker\Service\Kernel\AbstractBundleDependencyProvider|null $resolved */
        $resolved = $this->doResolve($callerClass);
        if ($resolved === null) {
            throw new DependencyProviderNotFoundException($this->getClassInfo());
        }

        return $resolved;
    }
}
