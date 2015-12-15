<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\ProductOptionCartConnector\Communication;

use Spryker\Zed\ProductOptionCartConnector\ProductOptionCartConnectorDependencyProvider;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationDependencyContainer;
use Spryker\Zed\ProductOptionCartConnector\Business\ProductOptionCartConnectorFacade;

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
