<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Permission\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\Permission\Business\PermissionExecutor\PermissionExecutor;
use Spryker\Zed\Permission\Business\PermissionFinder\PermissionFinder;
use Spryker\Zed\Permission\Business\PermissionSynchronizer\PermissionSynchronizer;
use Spryker\Zed\Permission\PermissionDependencyProvider;

/**
 * @method \Spryker\Zed\Permission\Persistence\PermissionRepositoryInterface getRepository()
 * @method \Spryker\Zed\Permission\Persistence\PermissionEntityManagerInterface getEntityManager()
 */
class PermissionBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\PermissionExtension\Dependency\Plugin\PermissionStoragePluginInterface[]
     */
    public function getPermissionStoragePlugins()
    {
        return $this->getProvidedDependency(PermissionDependencyProvider::PLUGINS_PERMISSION_STORAGE);
    }

    /**
     * @return \Spryker\Zed\Permission\Business\PermissionExecutor\PermissionExecutorInterface
     */
    public function createPermissionExecutor()
    {
        return new PermissionExecutor(
            $this->getPermissionStoragePlugins(),
            $this->createPermissionFinder()
        );
    }

    /**
     * @return \Spryker\Zed\Permission\Business\PermissionFinder\PermissionFinderInterface
     */
    public function createPermissionFinder()
    {
        return new PermissionFinder(
            $this->getPermissionPlugins()
        );
    }

    /**
     * @return \Spryker\Zed\Permission\Business\PermissionSynchronizer\PermissionSynchronizerInterface
     */
    public function createPermissionSynchronizer()
    {
        return new PermissionSynchronizer(
            $this->getPermissionClient(),
            $this->createPermissionFinder(),
            $this->getEntityManager()
        );
    }

    /**
     * @return \Spryker\Shared\PermissionExtension\Dependency\Plugin\PermissionPluginInterface[]
     */
    public function getPermissionPlugins()
    {
        return $this->getProvidedDependency(PermissionDependencyProvider::PLUGINS_PERMISSION);
    }

    /**
     * @return \Spryker\Client\Permission\PermissionClientInterface
     */
    public function getPermissionClient()
    {
        return $this->getProvidedDependency(PermissionDependencyProvider::CLIENT_PERMISSION);
    }
}
