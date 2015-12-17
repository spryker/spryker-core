<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Installer\Business;

use Spryker\Zed\Installer\Business\Model\AbstractInstaller;
use Spryker\Zed\Kernel\Business\AbstractFacade;
use Spryker\Zed\Installer\Business\Model\GlossaryInstaller;

/**
 * @method InstallerBusinessFactory getFactory()
 */
class InstallerFacade extends AbstractFacade
{

    /**
     * @return AbstractInstaller[]
     */
    public function getInstallers()
    {
        return $this->getFactory()->getInstallers();
    }

    /**
     * @return AbstractInstaller[]
     */
    public function getDemoDataInstallers()
    {
        return $this->getFactory()->getDemoDataInstallers();
    }

    /**
     * @return GlossaryInstaller
     */
    public function getGlossaryInstaller()
    {
        return $this->getFactory()->getGlossaryInstaller();
    }

}
