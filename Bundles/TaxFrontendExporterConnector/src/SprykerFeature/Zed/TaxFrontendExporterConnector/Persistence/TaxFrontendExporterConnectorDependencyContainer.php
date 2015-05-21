<?php

namespace SprykerFeature\Zed\TaxFrontendExporterConnector\Persistence;

use Generated\Zed\Ide\FactoryAutoCompletion\TaxFrontendExporterConnectorPersistence;
use SprykerEngine\Zed\Kernel\Persistence\AbstractDependencyContainer;
use SprykerFeature\Zed\Tax\Persistence\TaxQueryContainer;

/**
 * @method TaxFrontendExporterConnectorPersistence getFactory()
 */
class TaxFrontendExporterConnectorDependencyContainer extends AbstractDependencyContainer
{
    /**
     * @return ProductPriceExpanderInterface
     */
    public function createProductTaxExpander()
    {
        return $this->getFactory()->createProductTaxExpander(
            $this->getTaxQueryContainer()
        );
    }

    /**
     * @return TaxQueryContainer
     */
    protected function getTaxQueryContainer()
    {
        return $this->getLocator()->tax()->queryContainer();
    }
}
