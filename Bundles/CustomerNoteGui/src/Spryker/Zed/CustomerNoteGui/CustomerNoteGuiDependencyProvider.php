<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CustomerNoteGui;

use Spryker\Zed\CustomerNoteGui\Dependency\Facade\CustomerNoteGuiToCustomerNoteFacadeBridge;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;

class CustomerNoteGuiDependencyProvider extends AbstractBundleDependencyProvider
{
    public const FACADE_CUSTOMER_NOTE = 'FACADE_CUSTOMER_NOTE';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideCommunicationLayerDependencies(Container $container): Container
    {
        $container = parent::provideCommunicationLayerDependencies($container);
        $container = $this->addCustomerNoteFacade($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addCustomerNoteFacade(Container $container): Container
    {
        $container[static::FACADE_CUSTOMER_NOTE] = function (Container $container) {
            return new CustomerNoteGuiToCustomerNoteFacadeBridge($container->getLocator()->customerNote()->facade());
        };

        return $container;
    }
}
