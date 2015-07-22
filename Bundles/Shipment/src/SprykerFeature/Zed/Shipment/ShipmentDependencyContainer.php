<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Customer;

use SprykerFeature\Zed\Installer\Business\Model\InstallerInterface;
use SprykerEngine\Zed\Kernel\Communication\AbstractCommunicationDependencyContainer;

class ShipmentDependencyContainer extends AbstractCommunicationDependencyContainer
{

    /**
     * @return InstallerInterface
     */
    public function createInstaller()
    {
        return $this->getLocator()->customer()->facade();
    }

}
