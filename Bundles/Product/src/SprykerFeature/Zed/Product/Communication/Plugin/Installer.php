<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Product\Communication\Plugin;

use SprykerFeature\Zed\Installer\Communication\Plugin\AbstractInstallerPlugin;
use SprykerFeature\Zed\Product\Business\ProductFacade;
use SprykerFeature\Zed\Product\Communication\ProductDependencyContainer;

/**
 * @method ProductDependencyContainer getDependencyContainer()
 * @method ProductFacade getFacade()
 */
class Installer extends AbstractInstallerPlugin
{

    public function install()
    {
        $this->getFacade()->install($this->messenger);
    }

}
