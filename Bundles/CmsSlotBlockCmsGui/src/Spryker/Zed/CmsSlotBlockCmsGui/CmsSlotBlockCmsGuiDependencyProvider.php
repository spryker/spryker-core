<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsSlotBlockCmsGui;

use Spryker\Client\Kernel\AbstractDependencyProvider;
use Spryker\Zed\CmsSlotBlockCmsGui\Dependency\QueryContainer\CmsSlotBlockCmsGuiToCmsQueryContainerBridge;
use Spryker\Zed\Kernel\Container;

class CmsSlotBlockCmsGuiDependencyProvider extends AbstractDependencyProvider
{
    public const QUERY_CONTAINER_CMS = 'QUERY_CONTAINER_CMS';

    public function provideCommunicationLayerDependencies(Container $container): Container
    {
        $container = $this->addCmsQueryContainer($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addCmsQueryContainer(Container $container): Container
    {
        $container->set(static::QUERY_CONTAINER_CMS, function (Container $container) {
            return new CmsSlotBlockCmsGuiToCmsQueryContainerBridge($container->getLocator()->cms()->queryContainer());
        });
        return $container;
    }
}
