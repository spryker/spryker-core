<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\ProductFrontendExporterConnector\Persistence;

use Generated\Zed\Ide\FactoryAutoCompletion\ProductFrontendExporterConnectorPersistence;
use SprykerEngine\Zed\Kernel\Persistence\AbstractPersistenceDependencyContainer;
use SprykerFeature\Zed\Product\Persistence\ProductQueryContainerInterface;

/**
 * @method ProductFrontendExporterConnectorPersistence getFactory()
 */
class ProductFrontendExporterConnectorDependencyContainer extends AbstractPersistenceDependencyContainer
{

    /**
     * @return ProductQueryExpanderInterface
     */
    public function getProductQueryExpander()
    {
        return $this->getFactory()->createProductQueryExpander(
            $this->getProductQueryContainer()
        );
    }

    /**
     * @return ProductQueryContainerInterface
     */
    public function getProductQueryContainer()
    {
        return $this->getLocator()->product()->queryContainer();
    }

}
