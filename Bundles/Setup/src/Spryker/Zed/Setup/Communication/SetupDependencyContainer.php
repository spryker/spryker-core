<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Setup\Communication;

use Spryker\Zed\Kernel\Communication\AbstractCommunicationDependencyContainer;
use Spryker\Zed\Setup\SetupConfig;

/**
 * @method SetupConfig getConfig()
 */
class SetupDependencyContainer extends AbstractCommunicationDependencyContainer
{

    /**
     * @return array
     */
    public function createSetupInstallCommandNames()
    {
        return $this->getConfig()->getSetupInstallCommandNames();
    }

}
