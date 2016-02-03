<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\User\Communication\Plugin;

use Spryker\Zed\Installer\Communication\Plugin\AbstractInstallerPlugin;
use Spryker\Zed\Installer\Business\Model\InstallerInterface;

/**
 * @method \Spryker\Zed\User\Business\UserFacade getFacade()
 */
class Installer extends AbstractInstallerPlugin implements InstallerInterface
{

    /**
     * Main Installer Method
     *
     * @return void
     */
    public function install()
    {
        $this->getFacade()->install();
    }

}
