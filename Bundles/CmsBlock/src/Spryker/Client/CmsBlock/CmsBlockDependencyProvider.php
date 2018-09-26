<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\CmsBlock;

use Spryker\Client\CmsBlock\Dependency\Client\CmsBlockToStorageClientBridge;
use Spryker\Client\Kernel\AbstractDependencyProvider;
use Spryker\Client\Kernel\Container;

class CmsBlockDependencyProvider extends AbstractDependencyProvider
{
    public const KV_STORAGE = 'CLIENT:CMS_BLOCK:KV_STORAGE';

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    public function provideServiceLayerDependencies(Container $container)
    {
        $container = parent::provideServiceLayerDependencies($container);
        $container = $this->addKvStorage($container);

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addKvStorage(Container $container)
    {
        $container[static::KV_STORAGE] = function (Container $container) {
            return new CmsBlockToStorageClientBridge($container->getLocator()->storage()->client());
        };

        return $container;
    }
}
