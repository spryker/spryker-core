<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Kernel\Communication;

use Spryker\Shared\Kernel\ClassResolver\ResolverCacheFactoryInterface;
use Spryker\Shared\Kernel\ClassResolver\ResolverCacheManager;

/**
 * @method \Spryker\Zed\Kernel\KernelConfig getConfig()
 * @method \Spryker\Zed\Kernel\Business\KernelFacadeInterface getFacade()
 */
class KernelCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Spryker\Shared\Kernel\ClassResolver\ResolverCacheFactoryInterface
     */
    public function createResolverCacheManager(): ResolverCacheFactoryInterface
    {
        return new ResolverCacheManager();
    }
}
