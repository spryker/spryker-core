<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\Kernel\Backend\ClassResolver;

use Spryker\Glue\Kernel\ClassResolver\DependencyProvider\DependencyProviderResolver as GlueDependencyProviderResolver;

class DependencyProviderResolver extends GlueDependencyProviderResolver
{
    /**
     * @param object|string $callerClass
     *
     * @return \Spryker\Glue\Kernel\Backend\AbstractBundleDependencyProvider
     */
    public function resolve($callerClass)
    {
        /** @var \Spryker\Glue\Kernel\Backend\AbstractBundleDependencyProvider $resolvedDependencyProvider */
        $resolvedDependencyProvider = parent::resolve($callerClass);

        return $resolvedDependencyProvider;
    }
}
