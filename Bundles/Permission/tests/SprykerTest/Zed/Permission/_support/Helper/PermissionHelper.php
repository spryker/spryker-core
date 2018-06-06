<?php

/**
 * Copyright © 2018-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Permission\Helper;

use Codeception\Module;
use Generated\Shared\Transfer\PermissionTransfer;
use Spryker\Shared\PermissionExtension\Dependency\Plugin\PermissionPluginInterface;
use Spryker\Zed\Permission\Business\PermissionFacadeInterface;
use Spryker\Zed\Permission\PermissionDependencyProvider;
use SprykerTest\Shared\Testify\Helper\DependencyHelperTrait;
use SprykerTest\Shared\Testify\Helper\LocatorHelperTrait;

class PermissionHelper extends Module
{
    use DependencyHelperTrait;
    use LocatorHelperTrait;

    /**
     * @param \Spryker\Shared\PermissionExtension\Dependency\Plugin\PermissionPluginInterface $permissionPlugin
     *
     * @return \Generated\Shared\Transfer\PermissionTransfer
     */
    public function havePermission(PermissionPluginInterface $permissionPlugin): PermissionTransfer
    {
        $this->syncPermission($permissionPlugin);

        return $this->getPermissionFacade()->findPermissionByKey($permissionPlugin->getKey());
    }

    /**
     * @param \Spryker\Shared\PermissionExtension\Dependency\Plugin\PermissionPluginInterface $permissionPlugin
     *
     * @return void
     */
    protected function syncPermission(PermissionPluginInterface $permissionPlugin): void
    {
        $this->setDependency(PermissionDependencyProvider::PLUGINS_PERMISSION, [$permissionPlugin]);

        $this->getPermissionFacade()->syncPermissionPlugins();
    }

    /**
     * @return \Spryker\Zed\Permission\Business\PermissionFacadeInterface
     */
    protected function getPermissionFacade(): PermissionFacadeInterface
    {
        return $this->getLocator()->permission()->facade();
    }
}
