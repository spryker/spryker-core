<?php

namespace Spryker\Zed\CustomerAccess\Communication\Plugin;

use Spryker\Zed\Installer\Dependency\Plugin\InstallerPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\CustomerAccess\Business\CustomerAccessFacadeInterface getFacade()
 */
class CustomerAccessInstallerPlugin extends AbstractPlugin implements InstallerPluginInterface
{
    /**
     * @return void
     */
    public function install()
    {
        $this->getFacade()->install();
    }
}