<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\Kernel\ClassResolver\DependencyProvider;

use Spryker\Yves\Kernel\ClassResolver\AbstractClassResolver;

class DependencyProviderResolver extends AbstractClassResolver
{
    protected const RESOLVABLE_TYPE = 'YvesDependencyProvider';

    /**
     * @param object|string $callerClass
     *
     * @throws \Spryker\Yves\Kernel\ClassResolver\DependencyProvider\DependencyProviderNotFoundException
     *
     * @return \Spryker\Yves\Kernel\AbstractBundleDependencyProvider
     */
    public function resolve($callerClass)
    {
        $resolved = parent::doResolve($callerClass);

        if ($resolved !== null) {
            return $resolved;
        }

        throw new DependencyProviderNotFoundException($this->getClassInfo());
    }
}
