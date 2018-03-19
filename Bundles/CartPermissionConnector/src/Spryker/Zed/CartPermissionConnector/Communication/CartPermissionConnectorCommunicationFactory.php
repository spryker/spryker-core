<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CartPermissionConnector\Communication;

use Spryker\Zed\CartPermissionConnector\CartPermissionConnectorDependencyProvider;
use Spryker\Zed\CartPermissionConnector\Dependency\Facade\CartPermissionConnectorToMessengerFacadeInterface;
use Spryker\Zed\CartPermissionConnector\Dependency\Facade\CartPermissionConnectorToPermissionFacadeInterface;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;

class CartPermissionConnectorCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Spryker\Zed\CartPermissionConnector\Dependency\Facade\CartPermissionConnectorToPermissionFacadeInterface
     */
    public function getPermissionFacade(): CartPermissionConnectorToPermissionFacadeInterface
    {
        return $this->getProvidedDependency(CartPermissionConnectorDependencyProvider::FACADE_PERMISSION);
    }

    /**
     * @return \Spryker\Zed\CartPermissionConnector\Dependency\Facade\CartPermissionConnectorToMessengerFacadeInterface
     */
    public function getMessengerFacade(): CartPermissionConnectorToMessengerFacadeInterface
    {
        return $this->getProvidedDependency(CartPermissionConnectorDependencyProvider::FACADE_MESSENGER);
    }
}
