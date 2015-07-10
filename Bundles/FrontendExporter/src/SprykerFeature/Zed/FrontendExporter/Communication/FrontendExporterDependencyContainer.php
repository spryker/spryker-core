<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\FrontendExporter\Communication;

use SprykerEngine\Zed\Locale\Business\LocaleFacade;
use SprykerFeature\Zed\FrontendExporter\FrontendExporterDependencyProvider;
use SprykerFeature\Zed\Installer\Business\Model\AbstractInstaller;
use SprykerEngine\Zed\Kernel\Communication\AbstractCommunicationDependencyContainer;

class FrontendExporterDependencyContainer extends AbstractCommunicationDependencyContainer
{

    /**
     * @return AbstractInstaller
     */
    public function getInstallerFacade()
    {
        return $this->getLocator()->frontendExporter()->facade();
    }

    /**
     * @return LocaleFacade
     */
    public function createLocaleFacade()
    {
        return $this->getProvidedDependency(FrontendExporterDependencyProvider::FACADE_LOCALE);
    }

}
