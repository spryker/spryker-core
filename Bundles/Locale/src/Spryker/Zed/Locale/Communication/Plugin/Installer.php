<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Locale\Communication\Plugin;

use Spryker\Zed\Installer\Communication\Plugin\AbstractInstallerPlugin;
use Spryker\Zed\Locale\Business\LocaleFacade;
use Spryker\Zed\Locale\Communication\LocaleCommunicationFactory;

/**
 * @method LocaleCommunicationFactory getFactory()
 * @method LocaleFacade getFacade()
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
