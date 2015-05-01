<?php

/*
 * (c) Copyright Spryker Systems GmbH 2015
 */

namespace SprykerEngine\Zed\Locale\Communication\Plugin;

use SprykerFeature\Zed\Installer\Communication\Plugin\AbstractInstallerPlugin;
use SprykerEngine\Zed\Locale\Communication\LocaleDependencyContainer;

/**
 * @method LocaleDependencyContainer getDependencyContainer()
 */
class Installer extends AbstractInstallerPlugin
{

            public function install()
    {
        $this->getDependencyContainer()->getInstallerFacade()->install($this->messenger);
    }
}
