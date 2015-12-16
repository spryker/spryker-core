<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\ProductOptionCartConnector\Communication;

use Spryker\Zed\ProductOptionCartConnector\ProductOptionCartConnectorDependencyProvider;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\ProductOptionCartConnector\Business\ProductOptionCartConnectorFacade;

class ProductOptionCartConnectorCommunicationFactory extends AbstractCommunicationFactory
{

    /**
     * @return ProductOptionCartConnectorFacade
     */
    public function createFacade()
    {
        return $this->getProvidedDependency(ProductOptionCartConnectorDependencyProvider::FACADE_PRODUCT_OPTION_CART_CONNECTOR);
    }

}
