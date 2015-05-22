<?php

namespace SprykerFeature\Zed\TaxFrontendExporterConnector\Communication;

use SprykerEngine\Zed\Kernel\Communication\AbstractDependencyContainer;
use SprykerFeature\Zed\TaxFrontendExporterConnector\Business\TaxFrontendExporterConnectorFacade;
use SprykerFeature\Zed\TaxFrontendExporterConnector\Persistence\TaxFrontendExporterConnectorQueryContainer
    as QueryContainer;

class TaxFrontendExporterConnectorDependencyContainer extends AbstractDependencyContainer
{
    /**
     * @return TaxFrontendExporterConnectorFacade
     */
    public function getFacade()
    {
        return $this->getLocator()->taxFrontendExporterConnector()->facade();
    }

    /**
     * @return TaxFrontendExporterConnectorFacade
     */
    public function getTaxProcessor()
    {
        return $this->getFacade();
    }

    /**
     * @return QueryContainer
     */
    public function getQueryContainer()
    {
        return $this->getLocator()->taxFrontendExporterConnector()->queryContainer();
    }
}
