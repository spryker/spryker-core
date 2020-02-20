<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OrderCustomReferenceGui;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\OrderCustomReferenceGui\Dependency\Facade\OrderCustomReferenceGuiToOrderCustomReferenceFacadeBridge;

class OrderCustomReferenceGuiDependencyProvider extends AbstractBundleDependencyProvider
{
    public const FACADE_ORDER_CUSTOM_REFERENCE = 'FACADE_ORDER_CUSTOM_REFERENCE';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideCommunicationLayerDependencies(Container $container): Container
    {
        $container = parent::provideCommunicationLayerDependencies($container);
        $container = $this->addOrderCustomReferenceFacade($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addOrderCustomReferenceFacade(Container $container): Container
    {
        $container->set(static::FACADE_ORDER_CUSTOM_REFERENCE, function (Container $container) {
            return new OrderCustomReferenceGuiToOrderCustomReferenceFacadeBridge(
                $container->getLocator()->orderCustomReference()->facade()
            );
        });

        return $container;
    }
}
