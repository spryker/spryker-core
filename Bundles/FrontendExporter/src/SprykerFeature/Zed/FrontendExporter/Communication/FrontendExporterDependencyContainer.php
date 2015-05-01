<?php

namespace SprykerFeature\Zed\FrontendExporter\Communication;

use SprykerFeature\Zed\Installer\Business\Model\AbstractInstaller;
use SprykerEngine\Zed\Kernel\Communication\AbstractDependencyContainer;

class FrontendExporterDependencyContainer extends AbstractDependencyContainer
{
    /**
     * @return AbstractInstaller
     */
    public function getInstallerFacade()
    {
        return $this->getLocator()->frontendExporter()->facade();
    }
}
