<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Setup\Communication;

use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;

/**
 * @method \Spryker\Zed\Setup\SetupConfig getConfig()
 */
class SetupCommunicationFactory extends AbstractCommunicationFactory
{

    /**
     * @return array
     */
    public function getSetupInstallCommandNames()
    {
        return $this->getConfig()->getSetupInstallCommandNames();
    }

}
