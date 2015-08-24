<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\User\Communication\Plugin;

use SprykerFeature\Zed\User\Business\UserFacade;
use SprykerFeature\Zed\Installer\Business\Model\InstallerInterface;
use SprykerEngine\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method UserFacade getFacade()
 */
class Installer extends AbstractPlugin implements InstallerInterface
{

    /**
     * Main Installer Method
     */
    public function install()
    {
        $this->getFacade()->install();
    }

}
