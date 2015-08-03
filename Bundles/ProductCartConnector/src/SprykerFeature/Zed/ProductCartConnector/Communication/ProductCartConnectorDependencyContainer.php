<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\ProductCartConnector\Communication;

use SprykerEngine\Zed\Kernel\Communication\AbstractCommunicationDependencyContainer;
use SprykerFeature\Zed\ProductCartConnector\Business\ProductCartConnectorFacade;
use SprykerFeature\Zed\ProductCartConnector\ProductCartConnectorDependencyProvider;

class ProductCartConnectorDependencyContainer extends AbstractCommunicationDependencyContainer
{

    /**
     * @return ProductCartConnectorFacade
     */
    public function createFacade()
    {
        return $this->getProvidedDependency(ProductCartConnectorDependencyProvider::FACADE_PRODUCT_CART_CONNECTOR);
    }

}
