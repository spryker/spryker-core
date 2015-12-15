<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Collector\Communication\Plugin;

use Spryker\Zed\Collector\Communication\CollectorDependencyContainer;
use Spryker\Zed\Installer\Communication\Plugin\AbstractInstallerPlugin;

/**
 * @method CollectorDependencyContainer getDependencyContainer()
 */
class Installer extends AbstractInstallerPlugin
{

    /**
     * @return void
     */
    public function install()
    {
        $this->getDependencyContainer()->getInstallerFacade()->install($this->messenger);
    }

}
