<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Setup\Communication;

use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\Setup\SetupConfig;

/**
 * @method SetupConfig getConfig()
 */
class SetupDependencyContainer extends AbstractCommunicationFactory
{

    /**
     * @return array
     */
    public function createSetupInstallCommandNames()
    {
        return $this->getConfig()->getSetupInstallCommandNames();
    }

}
