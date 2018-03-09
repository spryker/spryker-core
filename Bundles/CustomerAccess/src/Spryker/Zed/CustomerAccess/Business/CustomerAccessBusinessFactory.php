<?php

namespace Spryker\Zed\CustomerAccess\Business;

use Spryker\Zed\CustomerAccess\Business\Installer\CustomerAccessInstaller;
use Spryker\Zed\CustomerAccess\Business\Model\CustomerAccessCreator;
use Spryker\Zed\CustomerAccess\Business\Model\CustomerAccessReader;
use Spryker\Zed\CustomerAccess\Business\Model\CustomerAccessUpdater;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;

/**
 * @method \Spryker\Zed\CustomerAccess\CustomerAccessConfig getConfig()
 * @method \Spryker\Zed\CustomerAccess\Persistence\CustomerAccessQueryContainerInterface getQueryContainer()
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
        return new CustomerAccessReader($this->getQueryContainer());
    }

    /**
     *
     * @return \Spryker\Zed\CustomerAccess\Business\Model\CustomerAccessUpdaterInterface
     */
    public function createCustomerAccessUpdater()
    {
        return new CustomerAccessUpdater($this->getQueryContainer());
    }
}