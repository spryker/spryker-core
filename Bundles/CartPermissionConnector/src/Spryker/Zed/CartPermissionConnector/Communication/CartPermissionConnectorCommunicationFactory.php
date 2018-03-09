<?php

namespace Spryker\Zed\CartPermissionConnector\Communication;

use Spryker\Zed\CartPermissionConnector\CartPermissionConnectorDependencyProvider;
use Spryker\Zed\CartPermissionConnector\Dependency\Facade\CartPermissionConnectorToMessengerFacadeBridge;
use Spryker\Zed\CartPermissionConnector\Dependency\Facade\CartPermissionConnectorToMessengerFacadeInterface;
use Spryker\Zed\CartPermissionConnector\Dependency\Facade\CartPermissionConnectorToPermissionFacadeInterface;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;

class CartPermissionConnectorCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return CartPermissionConnectorToPermissionFacadeInterface
     */
    public function getPermissionFacade(): CartPermissionConnectorToPermissionFacadeInterface
    {
        return $this->getProvidedDependency(CartPermissionConnectorDependencyProvider::FACADE_PERMISSION);
    }

    /**
     * @return CartPermissionConnectorToMessengerFacadeInterface
     */
    public function getMessengerFacade(): CartPermissionConnectorToMessengerFacadeInterface
    {
        return $this->getProvidedDependency(CartPermissionConnectorDependencyProvider::FACADE_MESSENGER);
    }
}