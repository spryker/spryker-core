<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Collector\Communication\Plugin;

use SprykerFeature\Zed\Collector\Communication\CollectorDependencyContainer;
use SprykerFeature\Zed\Installer\Communication\Plugin\AbstractInstallerPlugin;

/**
 * @method CollectorDependencyContainer getDependencyContainer()
 */
class Installer extends AbstractInstallerPlugin
{

    public function install()
    {
        $this->getDependencyContainer()->getInstallerFacade()->install($this->messenger);
    }

}
