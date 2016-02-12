<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Installer\Business;

use Spryker\Zed\Kernel\Business\AbstractFacade;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @method \Spryker\Zed\Installer\Business\InstallerBusinessFactory getFactory()
 */
class InstallerFacade extends AbstractFacade implements InstallerFacadeInterface
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
