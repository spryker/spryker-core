<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Customer\Communication\Plugin;

use SprykerFeature\Zed\Customer\Communication\CustomerDependencyContainer;
use SprykerFeature\Zed\Installer\Business\Model\InstallerInterface;
use SprykerEngine\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method CustomerDependencyContainer getDependencyContainer()
 */
class Installer extends AbstractPlugin implements InstallerInterface
{

    public function install()
    {
        $this->getDependencyContainer()->createInstaller()->install();
    }

}
