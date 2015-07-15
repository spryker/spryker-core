<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Country\Communication\Plugin;

use SprykerFeature\Zed\Installer\Communication\Plugin\AbstractInstallerPlugin;
use SprykerFeature\Zed\Country\Communication\CountryDependencyContainer;

/**
 * @method CountryDependencyContainer getDependencyContainer()
 */
class Installer extends AbstractInstallerPlugin
{

    public function install()
    {
        $this->getDependencyContainer()->getInstallerFacade()->install($this->messenger);
    }

}
