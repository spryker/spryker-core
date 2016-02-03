<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Price\Communication\Plugin;

use Spryker\Zed\Installer\Communication\Plugin\AbstractInstallerPlugin;

/**
 * @method \Spryker\Zed\Price\Communication\PriceCommunicationFactory getFactory()
 * @method \Spryker\Zed\Price\Business\PriceFacade getFacade()
 */
class Installer extends AbstractInstallerPlugin
{

    /**
     * @return void
     */
    public function install()
    {
        $this->getFacade()->install($this->messenger);
    }

}
