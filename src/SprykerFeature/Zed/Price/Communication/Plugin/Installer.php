<?php

namespace SprykerFeature\Zed\Price\Communication\Plugin;

use SprykerFeature\Zed\Installer\Communication\Plugin\AbstractInstallerPlugin;
use SprykerFeature\Zed\Price\Communication\PriceDependencyContainer;

/**
 * @method PriceDependencyContainer getDependencyContainer()
 */
class Installer extends AbstractInstallerPlugin
{

            public function install()
    {
        $this->getDependencyContainer()->getInstallerFacade()->install($this->messenger);
    }
}
