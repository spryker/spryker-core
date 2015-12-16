<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Locale\Communication\Plugin;

use Spryker\Zed\Installer\Communication\Plugin\AbstractInstallerPlugin;
use Spryker\Zed\Locale\Communication\LocaleCommunicationFactory;

/**
 * @method LocaleCommunicationFactory getCommunicationFactory()
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
