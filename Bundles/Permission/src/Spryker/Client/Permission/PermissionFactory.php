<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Permission;

use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Client\Permission\PermissionExecutor\PermissionExecutor;
use Spryker\Client\Permission\PermissionFinder\PermissionFinder;

class PermissionFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Client\Permission\Dependency\Client\PermissionToCustomerClientInterface
     */
    public function getCustomerClient()
    {
        return $this->getProvidedDependency(PermissionDependencyProvider::CLIENT_CUSTOMER);
    }

    /**
     * @return \Spryker\Client\Permission\PermissionFinder\PermissionFinderInterface
     */
    public function createPermissionConfigurator()
    {
        return new PermissionFinder(
            $this->getPermissionPlugins()
        );
    }

    /**
     * @return \Spryker\Client\Permission\PermissionExecutor\PermissionExecutorInterface
     */
    public function createPermissionExecutor()
    {
        return new PermissionExecutor(
            $this->getCustomerClient(),
            $this->createPermissionConfigurator()
        );
    }

    /**
     * @return \Spryker\Client\Permission\Plugin\PermissionPluginInterface[]
     */
    protected function getPermissionPlugins()
    {
        return $this->getProvidedDependency(PermissionDependencyProvider::PLUGINS_PERMISSION);
    }
}
