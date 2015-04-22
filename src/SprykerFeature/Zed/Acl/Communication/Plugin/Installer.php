<?php

namespace SprykerFeature\Zed\Acl\Communication\Plugin;

use SprykerFeature\Zed\Acl\Communication\AclDependencyContainer;
use SprykerFeature\Zed\Installer\Business\Model\InstallerInterface;
use SprykerEngine\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method AclDependencyContainer getDependencyContainer()
 */
class Installer extends AbstractPlugin implements InstallerInterface
{

    /**
     * Main Installer Method
     */
    public function install()
    {
        $this->getDependencyContainer()->locateAclFacade()->install();
    }
}
