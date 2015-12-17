<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Collector\Communication\Plugin;

use Spryker\Zed\Collector\Communication\CollectorCommunicationFactory;
use Spryker\Zed\Installer\Communication\Plugin\AbstractInstallerPlugin;

/**
 * @method CollectorCommunicationFactory getFactory()
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
