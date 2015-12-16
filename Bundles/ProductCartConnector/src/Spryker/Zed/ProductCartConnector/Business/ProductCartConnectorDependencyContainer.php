<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\ProductCartConnector\Business;

use Spryker\Zed\ProductCartConnector\Business\Manager\ProductManager;
use Spryker\Zed\ProductCartConnector\ProductCartConnectorDependencyProvider;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\ProductCartConnector\Business\Manager\ProductManagerInterface;
use Spryker\Zed\ProductCartConnector\ProductCartConnectorConfig;

/**
 * @method ProductCartConnectorDependencyContainer getBusinessFactory()
 * @method ProductCartConnectorConfig getConfig()
 */
class ProductCartConnectorDependencyContainer extends AbstractBusinessFactory
{

    /**
     * @return ProductManagerInterface
     */
    public function createProductManager()
    {
        return new ProductManager(
            $this->getProvidedDependency(ProductCartConnectorDependencyProvider::FACADE_PRODUCT)
        );
    }

}
