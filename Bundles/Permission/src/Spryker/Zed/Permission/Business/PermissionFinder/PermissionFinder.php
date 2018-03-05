<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Permission\Business\PermissionFinder;

use Generated\Shared\Transfer\PermissionCollectionTransfer;
use Generated\Shared\Transfer\PermissionTransfer;
use Spryker\Zed\Permission\Communication\Plugin\PermissionPluginInterface;

class PermissionFinder implements PermissionFinderInterface
{
    /**
     * @var \Spryker\Zed\Permission\Communication\Plugin\ExecutablePermissionPluginInterface[]
     */
    protected $permissionPlugins = [];

    /**
     * @param \Spryker\Zed\Permission\Communication\Plugin\PermissionPluginInterface[] $permissionPlugins
     */
    public function __construct(array $permissionPlugins)
    {
        $this->permissionPlugins = $this->indexPermissions($permissionPlugins);
    }

    /**
     * @param string $permissionKey
     *
     * @return null|\Spryker\Zed\Permission\Communication\Plugin\PermissionPluginInterface
     */
    public function findPermissionPlugin($permissionKey): ?PermissionPluginInterface
    {
        if (!isset($this->permissionPlugins[$permissionKey])) {
            return null;
        }

        return $this->permissionPlugins[$permissionKey];
    }

    /**
     * @return \Generated\Shared\Transfer\PermissionCollectionTransfer
     */
    public function getRegisteredPermissions(): PermissionCollectionTransfer
    {
        $permissionCollectionTransfer = new PermissionCollectionTransfer();

        foreach ($this->permissionPlugins as $permissionPlugin) {
            $permissionTransfer = (new PermissionTransfer())->setKey($permissionPlugin->getKey());
            $permissionCollectionTransfer->addPermission($permissionTransfer);
        }

        return $permissionCollectionTransfer;
    }

    /**
     * @param \Spryker\Zed\Permission\Communication\Plugin\PermissionPluginInterface[] $permissionPlugins
     *
     * @return array
     */
    protected function indexPermissions(array $permissionPlugins): array
    {
        $plugins = [];

        foreach ($permissionPlugins as $permissionPlugin) {
            $plugins[$permissionPlugin->getKey()] = $permissionPlugin;
        }

        return $plugins;
    }
}
