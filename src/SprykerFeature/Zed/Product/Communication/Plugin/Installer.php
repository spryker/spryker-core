<?php

namespace SprykerFeature\Zed\Product\Communication\Plugin;

use SprykerFeature\Zed\Installer\Communication\Plugin\AbstractInstallerPlugin;
use SprykerFeature\Zed\Product\Communication\ProductDependencyContainer;

/**
 * @method ProductDependencyContainer getDependencyContainer()
 */
class Installer extends AbstractInstallerPlugin
{

            public function install()
    {
        $this->getDependencyContainer()->getInstallerFacade()->install($this->messenger);
    }
}
