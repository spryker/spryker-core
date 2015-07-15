<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\ProductFrontendExporterConnector\Communication;

use Generated\Zed\Ide\FactoryAutoCompletion\ProductFrontendExporterConnectorCommunication;
use SprykerEngine\Zed\Kernel\Communication\AbstractCommunicationDependencyContainer;
use SprykerFeature\Zed\ProductFrontendExporterConnector\Business\ProductFrontendExporterConnectorFacade;
use SprykerFeature\Zed\ProductFrontendExporterConnector\Persistence\ProductFrontendExporterConnectorQueryContainerInterface;

/**
 * @property ProductFrontendExporterConnectorCommunication $factory
 */
class ProductFrontendExporterConnectorDependencyContainer extends AbstractCommunicationDependencyContainer
{

    /**
     * @return ProductFrontendExporterConnectorFacade
     */
    public function getProductProcessor()
    {
        return $this->getFacade();
    }

    /**
     * @return ProductFrontendExporterConnectorFacade
     */
    public function getProductExportDecider()
    {
        return $this->getFacade();
    }

    /**
     * @return ProductFrontendExporterConnectorFacade
     */
    public function getFacade()
    {
        return $this->getLocator()->productFrontendExporterConnector()->facade();
    }

    /**
     * @return ProductFrontendExporterConnectorQueryContainerInterface
     */
    public function getProductFrontendExporterConnectorQueryContainer()
    {
        return $this->getLocator()->productFrontendExporterConnector()->queryContainer();
    }

}
