<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Country\Communication\Plugin;

use Spryker\Zed\Country\Business\CountryFacade;
use Spryker\Zed\Installer\Communication\Plugin\AbstractInstallerPlugin;
use Spryker\Zed\Country\Communication\CountryCommunicationFactory;

/**
 * @method CountryCommunicationFactory getFactory()
 * @method CountryFacade getFacade()
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
