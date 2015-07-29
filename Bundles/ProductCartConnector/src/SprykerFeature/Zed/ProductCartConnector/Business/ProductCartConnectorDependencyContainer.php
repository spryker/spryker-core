<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\ProductCartConnector\Business;

use SprykerFeature\Zed\ProductCartConnector\ProductCartConnectorDependencyProvider;
use Generated\Zed\Ide\FactoryAutoCompletion\ProductCartConnectorBusiness;
use SprykerEngine\Zed\Kernel\Business\AbstractBusinessDependencyContainer;
use SprykerFeature\Zed\ProductCartConnector\Business\Manager\ProductManagerInterface;
use SprykerFeature\Zed\ProductCartConnector\ProductCartConnectorConfig;

/**
 * @method ProductCartConnectorDependencyContainer getDependencyContainer()
 * @method ProductCartConnectorBusiness getFactory()
 * @method ProductCartConnectorConfig getConfig()
 */
class ProductCartConnectorDependencyContainer extends AbstractBusinessDependencyContainer
{

    /**
     * @return ProductManagerInterface
     */
    public function createProductManager()
    {
        return $this->getFactory()->createManagerProductManager(
            $this->getProvidedDependency(ProductCartConnectorDependencyProvider::FACADE_PRODUCT)
        );
    }

}
