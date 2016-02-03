<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Country\Communication\Plugin;

use Spryker\Zed\Installer\Communication\Plugin\AbstractInstallerPlugin;

/**
 * @method \Spryker\Zed\Country\Communication\CountryCommunicationFactory getFactory()
 * @method \Spryker\Zed\Country\Business\CountryFacade getFacade()
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
