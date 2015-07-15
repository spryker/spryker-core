<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\TaxFrontendExporterConnector\Persistence;

use Generated\Zed\Ide\FactoryAutoCompletion\TaxFrontendExporterConnectorPersistence;
use SprykerEngine\Zed\Kernel\Persistence\AbstractPersistenceDependencyContainer;
use SprykerFeature\Zed\Tax\Persistence\TaxQueryContainerInterface;

/**
 * @method TaxFrontendExporterConnectorPersistence getFactory()
 */
class TaxFrontendExporterConnectorDependencyContainer extends AbstractPersistenceDependencyContainer
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
     * @return TaxQueryContainerInterface
     */
    protected function getTaxQueryContainer()
    {
        return $this->getLocator()->tax()->queryContainer();
    }

}
