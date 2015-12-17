<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Country\Communication\Plugin;

use Spryker\Zed\Installer\Communication\Plugin\AbstractInstallerPlugin;
use Spryker\Zed\Country\Communication\CountryCommunicationFactory;

/**
 * @method CountryCommunicationFactory getFactory()
 */
class Installer extends AbstractInstallerPlugin
{

    /**
     * @return void
     */
    public function install()
    {
        $this->getFactory()->getInstallerFacade()->install($this->messenger);
    }

}
