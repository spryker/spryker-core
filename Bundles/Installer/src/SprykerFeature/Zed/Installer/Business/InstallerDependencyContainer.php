<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Installer\Business;

use Generated\Zed\Ide\FactoryAutoCompletion\InstallerBusiness;
use SprykerFeature\Zed\Installer\Business\Model\AbstractInstaller;
use SprykerEngine\Zed\Kernel\Business\AbstractDependencyContainer;
use SprykerFeature\Zed\Installer\InstallerConfig;

/**
 * @method InstallerBusiness getFactory()
 * @method InstallerConfig getConfig()
 */
class InstallerDependencyContainer extends AbstractDependencyContainer
{

    /**
     * @return AbstractInstaller[]
     */
    public function getInstaller()
    {
        return $this->getConfig()->getInstallerStack();
    }

    /**
     * @return AbstractInstaller[]
     */
    public function getDemoDataInstaller()
    {
        return $this->getConfig()->getDemoDataInstallerStack();
    }
}
