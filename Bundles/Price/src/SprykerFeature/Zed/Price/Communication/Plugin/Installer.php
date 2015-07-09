<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Price\Communication\Plugin;

use SprykerFeature\Zed\Installer\Communication\Plugin\AbstractInstallerPlugin;
use SprykerFeature\Zed\Price\Business\PriceFacade;
use SprykerFeature\Zed\Price\Communication\PriceDependencyContainer;

/**
 * @method PriceDependencyContainer getDependencyContainer()
 * @method PriceFacade getFacade()
 */
class Installer extends AbstractInstallerPlugin
{

    public function install()
    {
        $this->getFacade()->install($this->messenger);
    }

}
