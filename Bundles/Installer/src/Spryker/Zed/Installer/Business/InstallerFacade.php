<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Installer\Business;

use Spryker\Zed\Installer\Business\Model\AbstractInstaller;
use Spryker\Zed\Kernel\Business\AbstractFacade;

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
