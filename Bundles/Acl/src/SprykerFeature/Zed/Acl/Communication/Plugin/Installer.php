<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Acl\Communication\Plugin;

use SprykerFeature\Zed\Acl\Business\AclFacade;
use SprykerFeature\Zed\Installer\Business\Model\InstallerInterface;
use SprykerEngine\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method AclFacade getFacade()
 */
class Installer extends AbstractPlugin implements InstallerInterface
{

    public function install()
    {
        $this->getFacade()->install();
    }

}
