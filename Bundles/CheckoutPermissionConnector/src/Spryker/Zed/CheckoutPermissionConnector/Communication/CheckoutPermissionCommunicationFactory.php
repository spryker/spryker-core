<?php

namespace Spryker\Zed\CheckoutPermissionConnector\Communication;


use Spryker\Zed\CheckoutPermissionConnector\CheckoutPermissionConnectorDependencyProvider;
use Spryker\Zed\CheckoutPermissionConnector\Dependency\CheckoutPermissionConnectorToFacadeInterface;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;

class CheckoutPermissionCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return CheckoutPermissionConnectorToFacadeInterface
     */
    public function getPermissionFacade()
    {
        return $this->getProvidedDependency(CheckoutPermissionConnectorDependencyProvider::FACADE_PERMISSION);
    }
}