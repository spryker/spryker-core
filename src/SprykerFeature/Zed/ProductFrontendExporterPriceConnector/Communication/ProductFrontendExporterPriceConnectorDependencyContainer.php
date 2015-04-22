<?php

namespace SprykerFeature\Zed\ProductFrontendExporterPriceConnector\Communication;

use SprykerEngine\Zed\Kernel\Communication\AbstractDependencyContainer;
use SprykerFeature\Zed\ProductFrontendExporterPriceConnector\Business\ProductFrontendExporterPriceConnectorFacade;
use SprykerFeature\Zed\ProductFrontendExporterPriceConnector\Persistence\ProductFrontendExporterPriceConnectorQueryContainer
    as QueryContainer;

class ProductFrontendExporterPriceConnectorDependencyContainer extends AbstractDependencyContainer
{

    /**
     * @return ProductFrontendExporterPriceConnectorFacade
     */
    public function getFacade()
    {
        return $this->getLocator()->productFrontendExporterPriceConnector()->facade();
    }

    /**
     * @return ProductFrontendExporterPriceConnectorFacade
     */
    public function getPriceProcessor()
    {
        return $this->getFacade();
    }

    /**
     * @return QueryContainer
     */
    public function getQueryContainer()
    {
        return $this->getLocator()->productFrontendExporterPriceConnector()->queryContainer();
    }
}
