<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Setup\Communication;

use SprykerEngine\Zed\Kernel\Communication\AbstractCommunicationDependencyContainer;
use SprykerFeature\Zed\Setup\SetupConfig;

/**
 * @method SetupConfig getConfig()
 */
class SetupDependencyContainer extends AbstractCommunicationDependencyContainer
{

    public function createSetupInstallCommandNames()
    {
        return $this->getConfig()->getSetupInstallCommandNames();
    }

}
