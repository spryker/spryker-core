<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\ProductCartConnector\Communication;

use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\ProductCartConnector\Business\ProductCartConnectorFacade;
use Spryker\Zed\ProductCartConnector\ProductCartConnectorDependencyProvider;
use Spryker\Zed\ProductCartConnector\ProductCartConnectorConfig;

/**
 * @method ProductCartConnectorConfig getConfig()
 */
class ProductCartConnectorCommunicationFactory extends AbstractCommunicationFactory
{

    /**
     * @return ProductCartConnectorFacade
     */
    public function createFacade()
    {
        return $this->getProvidedDependency(ProductCartConnectorDependencyProvider::FACADE_PRODUCT_CART_CONNECTOR);
    }

}
