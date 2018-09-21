<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CustomerAccess\Business;

use Spryker\Zed\CustomerAccess\Business\Installer\CustomerAccessInstaller;
use Spryker\Zed\CustomerAccess\Business\Installer\CustomerAccessInstallerInterface;
use Spryker\Zed\CustomerAccess\Business\Model\CustomerAccess;
use Spryker\Zed\CustomerAccess\Business\Model\CustomerAccessInterface;
use Spryker\Zed\CustomerAccess\Business\Model\CustomerAccessReader;
use Spryker\Zed\CustomerAccess\Business\Model\CustomerAccessReaderInterface;
use Spryker\Zed\CustomerAccess\Business\Model\CustomerAccessUpdater;
use Spryker\Zed\CustomerAccess\Business\Model\CustomerAccessUpdaterInterface;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;

/**
 * @method \Spryker\Zed\CustomerAccess\CustomerAccessConfig getConfig()
 * @method \Spryker\Zed\CustomerAccess\Persistence\CustomerAccessRepositoryInterface getRepository()
 * @method \Spryker\Zed\CustomerAccess\Persistence\CustomerAccessEntityManagerInterface getEntityManager()
 */
class CustomerAccessBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\CustomerAccess\Business\Installer\CustomerAccessInstallerInterface
     */
    public function createInstaller(): CustomerAccessInstallerInterface
    {
        return new CustomerAccessInstaller(
            $this->getConfig(),
            $this->createCustomerAccess(),
            $this->createCustomerAccessReader()
        );
    }

    /**
     * @return \Spryker\Zed\CustomerAccess\Business\Model\CustomerAccessInterface
     */
    public function createCustomerAccess(): CustomerAccessInterface
    {
        return new CustomerAccess($this->getEntityManager());
    }

    /**
     * @return \Spryker\Zed\CustomerAccess\Business\Model\CustomerAccessReaderInterface
     */
    public function createCustomerAccessReader(): CustomerAccessReaderInterface
    {
        return new CustomerAccessReader($this->getRepository());
    }

    /**
     * @return \Spryker\Zed\CustomerAccess\Business\Model\CustomerAccessUpdaterInterface
     */
    public function createCustomerAccessUpdater(): CustomerAccessUpdaterInterface
    {
        return new CustomerAccessUpdater($this->getEntityManager());
    }
}
