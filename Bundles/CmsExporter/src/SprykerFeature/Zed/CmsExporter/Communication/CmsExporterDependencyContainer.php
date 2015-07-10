<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\CmsExporter\Communication;

use SprykerEngine\Zed\Kernel\Communication\AbstractCommunicationDependencyContainer;
use SprykerFeature\Zed\CmsExporter\Business\CmsExporterFacade;
use SprykerFeature\Zed\CmsExporter\Persistence\CmsExporterQueryContainerInterface;

class CmsExporterDependencyContainer extends AbstractCommunicationDependencyContainer
{

    /**
     * @return CmsExporterQueryContainerInterface
     */
    public function getCmsExporterQueryContainer()
    {
        return $this->getLocator()->cmsExporter()->queryContainer();
    }

    /**
     * @return CmsExporterFacade
     */
    public function getCmsExporterFacade()
    {
        return $this->getLocator()->cmsExporter()->facade();
    }

}
