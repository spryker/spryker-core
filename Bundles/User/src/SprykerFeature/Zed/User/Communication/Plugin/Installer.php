<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\User\Communication\Plugin;

use SprykerFeature\Zed\User\Communication\UserDependencyContainer;
use SprykerFeature\Zed\Installer\Business\Model\InstallerInterface;
use SprykerEngine\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method UserDependencyContainer getDependencyContainer()
 */
class Installer extends AbstractPlugin implements InstallerInterface
{

    /**
     * Main Installer Method
     */
    public function install()
    {
        $this->getDependencyContainer()->getInstallerFacade()->install();
    }

}
