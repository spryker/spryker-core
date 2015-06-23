<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\ProductFrontendExporterPriceConnector\Persistence;

use Generated\Zed\Ide\FactoryAutoCompletion\ProductFrontendExporterPriceConnectorPersistence;
use SprykerEngine\Zed\Kernel\Persistence\AbstractDependencyContainer;
use SprykerFeature\Zed\Product\Persistence\ProductQueryContainerInterface;

/**
 * @method ProductFrontendExporterPriceConnectorPersistence getFactory()
 */
class ProductFrontendExporterPriceConnectorDependencyContainer extends AbstractDependencyContainer
{
    /**
     * @return ProductPriceExpanderInterface
     */
    public function createProductPriceExpander()
    {
        return $this->getFactory()->createProductPriceExpander(
            $this->getProductQueryContainer()
        );
    }

    /**
     * @return ProductQueryContainerInterface
     */
    protected function getProductQueryContainer()
    {
        return $this->getLocator()->product()->queryContainer();
    }
}
