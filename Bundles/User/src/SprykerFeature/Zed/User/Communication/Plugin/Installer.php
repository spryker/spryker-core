<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\User\Communication\Plugin;

use SprykerFeature\Zed\Installer\Communication\Plugin\AbstractInstallerPlugin;
use SprykerFeature\Zed\User\Business\UserFacade;
use SprykerFeature\Zed\Installer\Business\Model\InstallerInterface;

/**
 * @method UserFacade getFacade()
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
