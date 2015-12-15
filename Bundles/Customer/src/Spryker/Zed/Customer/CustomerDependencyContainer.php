<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Customer;

use Spryker\Zed\Installer\Business\Model\InstallerInterface;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationDependencyContainer;

class CustomerDependencyContainer extends AbstractCommunicationDependencyContainer
{

    /**
     * @return InstallerInterface
     */
    public function createInstaller()
    {
        return $this->getLocator()->customer()->facade();
    }

}
