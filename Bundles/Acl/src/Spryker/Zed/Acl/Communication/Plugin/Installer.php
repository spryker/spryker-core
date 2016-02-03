<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Acl\Communication\Plugin;

use Spryker\Zed\Installer\Business\Model\InstallerInterface;
use Spryker\Zed\Installer\Communication\Plugin\AbstractInstallerPlugin;

/**
 * @method \Spryker\Zed\Acl\Business\AclFacade getFacade()
 */
class Installer extends AbstractInstallerPlugin implements InstallerInterface
{

    /**
     * @return void
     */
    public function install()
    {
        $this->getFacade()->install();
    }

}
