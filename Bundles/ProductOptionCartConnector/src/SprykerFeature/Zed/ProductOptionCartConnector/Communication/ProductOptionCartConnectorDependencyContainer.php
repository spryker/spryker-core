<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\ProductOptionCartConnector\Communication;

use SprykerFeature\Zed\ProductOptionCartConnector\ProductOptionCartConnectorDependencyProvider;
use SprykerEngine\Zed\Kernel\Communication\AbstractCommunicationDependencyContainer;
use SprykerFeature\Zed\ProductOptionCartConnector\Business\ProductOptionCartConnectorFacade;

class ProductOptionCartConnectorDependencyContainer extends AbstractCommunicationDependencyContainer
{
    /**
     * @return ProductOptionCartConnectorFacade
     */
    public function createFacade()
    {
        return $this->getProvidedDependency(ProductOptionCartConnectorDependencyProvider::FACADE_PRODUCT_OPTION_CART_CONNECTOR);
    }
}
