<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\ProductOptionCartConnector\Business;

use Generated\Zed\Ide\FactoryAutoCompletion\ProductOptionCartConnectorBusiness;
use SprykerEngine\Zed\Kernel\Business\AbstractBusinessDependencyContainer;
use SprykerFeature\Zed\ProductOptionCartConnector\Business\Model\GroupKeyExpander;
use SprykerFeature\Zed\ProductOptionCartConnector\ProductOptionCartConnectorDependencyProvider;
use SprykerFeature\Zed\ProductOptionCartConnector\Business\Manager\ProductOptionManagerInterface;
use SprykerFeature\Zed\ProductOptionCartConnector\ProductOptionCartConnectorConfig;

/**
 * @method ProductOptionCartConnectorDependencyContainer getDependencyContainer()
 * @method ProductOptionCartConnectorBusiness getFactory()
 * @method ProductOptionCartConnectorConfig getConfig()
 */
class ProductOptionCartConnectorDependencyContainer extends AbstractBusinessDependencyContainer
{

    /**
     * @return ProductOptionManagerInterface
     */
    public function createProductOptionManager()
    {
        return $this->getFactory()->createManagerProductOptionManager(
            $this->getProvidedDependency(ProductOptionCartConnectorDependencyProvider::FACADE_PRODUCT_OPTION)
        );
    }

    /**
     * @return GroupKeyExpander
     */
    public function createGroupKeyExpander()
    {
        return $this->getFactory()->createModelGroupKeyExpander();
    }
}
