<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Customer\Communication\Plugin;

use Spryker\Zed\Customer\Communication\CustomerPluginFactory;
use Spryker\Zed\Installer\Business\Model\InstallerInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method CustomerPluginFactory getFactory()
 */
class Installer extends AbstractPlugin implements InstallerInterface
{

    /**
     * @return void
     */
    public function install()
    {
        $this->getFactory()->createInstaller()->install();
    }

}
