<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CustomerNoteGui;

use Spryker\Zed\CustomerNoteGui\Dependency\CustomerNoteGuiToCustomerNoteFacadeBridge;
use Spryker\Zed\CustomerNoteGui\Dependency\CustomerNoteGuiToUserFacadeBridge;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;

class CustomerNoteGuiDependencyProvider extends AbstractBundleDependencyProvider
{
    protected const FACADE_CUSTOMER_NOTE = 'FACADE_CUSTOMER_NOTE';
    protected const FACADE_USER = 'FACADE_USER';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideCommunicationLayerDependencies(Container $container)
    {
        $container = parent::provideCommunicationLayerDependencies($container);
        $container = $this->addCustomerNoteFacade($container);
        $container = $this->addUserFacade($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addCustomerNoteFacade(Container $container)
    {
        $container[static::FACADE_CUSTOMER_NOTE] = function (Container $container) {
            return new CustomerNoteGuiToCustomerNoteFacadeBridge($container->getLocator()->customerNote()->facade());
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addUserFacade(Container $container)
    {
        $container[static::FACADE_USER] = function (Container $container) {
            return new CustomerNoteGuiToUserFacadeBridge($container->getLocator()->user()->facade());
        };

        return $container;
    }
}
