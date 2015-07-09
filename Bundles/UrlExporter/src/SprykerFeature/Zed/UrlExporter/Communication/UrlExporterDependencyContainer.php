<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\UrlExporter\Communication;

use SprykerEngine\Zed\Kernel\Communication\AbstractCommunicationDependencyContainer;
use SprykerFeature\Zed\UrlExporter\Business\UrlExporterFacade;
use SprykerFeature\Zed\UrlExporter\Persistence\UrlExporterQueryContainerInterface;

class UrlExporterDependencyContainer extends AbstractCommunicationDependencyContainer
{

    /**
     * @return UrlExporterFacade
     */
    public function getUrlExporterFacade()
    {
        return $this->getLocator()->urlExporter()->facade();
    }

    /**
     * @return UrlExporterQueryContainerInterface
     */
    public function getUrlExporterQueryContainer()
    {
        return $this->getLocator()->urlExporter()->queryContainer();
    }

}
