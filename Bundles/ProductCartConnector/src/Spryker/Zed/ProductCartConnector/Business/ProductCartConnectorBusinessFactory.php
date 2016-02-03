<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\ProductCartConnector\Business;

use Spryker\Zed\ProductCartConnector\Business\Manager\ProductManager;
use Spryker\Zed\ProductCartConnector\ProductCartConnectorDependencyProvider;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;

/**
 * @method \Spryker\Zed\ProductCartConnector\Business\ProductCartConnectorBusinessFactory getFactory()
 * @method \Spryker\Zed\ProductCartConnector\ProductCartConnectorConfig getConfig()
 */
class ProductCartConnectorBusinessFactory extends AbstractBusinessFactory
{

    /**
     * @return \Spryker\Zed\ProductCartConnector\Business\Manager\ProductManagerInterface
     */
    public function createProductManager()
    {
        return new ProductManager(
            $this->getProvidedDependency(ProductCartConnectorDependencyProvider::FACADE_PRODUCT)
        );
    }

}
