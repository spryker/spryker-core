<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Country\Communication\Plugin;

use Spryker\Zed\Installer\Communication\Plugin\AbstractInstallerPlugin;
use Spryker\Zed\Country\Communication\CountryCommunicationFactory;

/**
 * @method CountryCommunicationFactory getCommunicationFactory()
 */
class Installer extends AbstractInstallerPlugin
{

    /**
     * @return void
     */
    public function install()
    {
        $this->getCommunicationFactory()->getInstallerFacade()->install($this->messenger);
    }

}
