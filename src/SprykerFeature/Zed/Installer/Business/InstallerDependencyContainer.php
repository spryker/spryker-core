<?php

namespace SprykerFeature\Zed\Installer\Business;

use Generated\Zed\Ide\FactoryAutoCompletion\InstallerBusiness;
use SprykerFeature\Zed\Installer\Business\Model\AbstractInstaller;
use SprykerEngine\Zed\Kernel\Business\AbstractDependencyContainer;

/**
 * @method InstallerBusiness getFactory()
 */
class InstallerDependencyContainer extends AbstractDependencyContainer
{

    /**
     * @return AbstractInstaller[]
     */
    public function getInstaller()
    {
        return $this->getFactory()->createInstallerSettings($this->getLocator())->getInstallerStack();
    }

    /**
     * @return AbstractInstaller[]
     */
    public function getDemoDataInstaller()
    {
        return $this->getFactory()->createInstallerSettings($this->getLocator())->getDemoDataInstallerStack();
    }
}
