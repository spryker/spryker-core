<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Installer\Business;

use SprykerFeature\Zed\Installer\Business\Model\AbstractInstaller;
use SprykerEngine\Zed\Kernel\Business\AbstractFacade;

/**
 * @method InstallerDependencyContainer getDependencyContainer()
 */
class InstallerFacade extends AbstractFacade
{

    /**
     * @return AbstractInstaller[]
     */
    public function getInstaller()
    {
        return $this->getDependencyContainer()->getInstaller();
    }

    /**
     * @return AbstractInstaller[]
     */
    public function getDemoDataInstaller()
    {
        return $this->getDependencyContainer()->getDemoDataInstaller();
    }

    /**
     * @return AbstractInstaller[]
     */
    public function getGlossaryInstaller()
    {
        return $this->getDependencyContainer()->getGlossaryInstaller();
    }

}
