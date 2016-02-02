<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Installer\Business;

use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method InstallerBusinessFactory getFactory()
 */
class InstallerFacade extends AbstractFacade
{

    /**
     * @return \Spryker\Zed\Installer\Business\Model\AbstractInstaller[]
     */
    public function getInstallers()
    {
        return $this->getFactory()->getInstallers();
    }

    /**
     * @return \Spryker\Zed\Installer\Business\Model\AbstractInstaller[]
     */
    public function getDemoDataInstallers()
    {
        return $this->getFactory()->getDemoDataInstallers();
    }

    /**
     * @return \Spryker\Zed\Installer\Business\Model\GlossaryInstaller
     */
    public function getGlossaryInstaller()
    {
        return $this->getFactory()->createGlossaryInstaller();
    }

}
