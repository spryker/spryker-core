<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\TaxFrontendExporterConnector\Communication;

use SprykerEngine\Zed\Kernel\Communication\AbstractCommunicationDependencyContainer;
use SprykerFeature\Zed\TaxFrontendExporterConnector\Business\TaxFrontendExporterConnectorFacade;
use SprykerFeature\Zed\TaxFrontendExporterConnector\Persistence\TaxFrontendExporterConnectorQueryContainer
    as QueryContainer;

class TaxFrontendExporterConnectorDependencyContainer extends AbstractCommunicationDependencyContainer
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
