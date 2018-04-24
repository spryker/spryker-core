<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CustomerAccess\Business;

use Spryker\Zed\CustomerAccess\Business\Installer\CustomerAccessInstaller;
use Spryker\Zed\CustomerAccess\Business\Model\CustomerAccessCreator;
use Spryker\Zed\CustomerAccess\Business\Model\CustomerAccessReader;
use Spryker\Zed\CustomerAccess\Business\Model\CustomerAccessUpdater;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;

/**
 * @method \Spryker\Zed\CustomerAccess\CustomerAccessConfig getConfig()
 * @method \Spryker\Zed\CustomerAccess\Persistence\CustomerAccessRepositoryInterface getRepository()
 */
class CustomerAccessBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\CustomerAccess\Business\Installer\CustomerAccessInstallerInterface
     */
    public function createInstaller()
    {
        return new CustomerAccessInstaller(
            $this->getConfig(),
            $this->createCustomerAccessCreator(),
            $this->createCustomerAccessReader()
        );
    }

    /**
     * @return \Spryker\Zed\CustomerAccess\Business\Model\CustomerAccessCreatorInterface
     */
    public function createCustomerAccessCreator()
    {
        return new CustomerAccessCreator();
    }

    /**
     * @return \Spryker\Zed\CustomerAccess\Business\Model\CustomerAccessReaderInterface
     */
    public function createCustomerAccessReader()
    {
        return new CustomerAccessReader($this->getRepository());
    }

    /**
     * @return \Spryker\Zed\CustomerAccess\Business\Model\CustomerAccessUpdaterInterface
     */
    public function createCustomerAccessUpdater()
    {
        return new CustomerAccessUpdater($this->getQueryContainer());
    }
}
