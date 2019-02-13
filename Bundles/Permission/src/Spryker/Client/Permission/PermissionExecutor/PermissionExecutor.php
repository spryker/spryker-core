<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Permission\PermissionExecutor;

use Generated\Shared\Transfer\PermissionCollectionTransfer;
use Generated\Shared\Transfer\PermissionTransfer;
use Spryker\Client\Permission\PermissionFinder\PermissionFinderInterface;
use Spryker\Shared\PermissionExtension\Dependency\Plugin\ExecutablePermissionPluginInterface;

class PermissionExecutor implements PermissionExecutorInterface
{
    /**
     * @var \Spryker\Client\PermissionExtension\Dependency\Plugin\PermissionStoragePluginInterface[]
     */
    protected $permissionStoragePlugins;

    /**
     * @var \Spryker\Client\Permission\PermissionFinder\PermissionFinderInterface
     */
    protected $permissionFinder;

    /**
     * @param \Spryker\Client\PermissionExtension\Dependency\Plugin\PermissionStoragePluginInterface[] $permissionStoragePlugins
     * @param \Spryker\Client\Permission\PermissionFinder\PermissionFinderInterface $permissionConfigurator
     */
    public function __construct(
        array $permissionStoragePlugins,
        PermissionFinderInterface $permissionConfigurator
    ) {
        $this->permissionFinder = $permissionConfigurator;
        $this->permissionStoragePlugins = $permissionStoragePlugins;
    }

    /**
     * @param string $permissionKey
     * @param string|int|array|null $context
     *
     * @return bool
     */
    public function can($permissionKey, $context = null): bool
    {
        $permissionPlugin = $this->permissionFinder->findPermissionPlugin($permissionKey);

        if (!$permissionPlugin) {
             return true;
        }

        $permissionCollectionTransfer = $this->findPermissions($permissionKey);

        if ($permissionCollectionTransfer->getPermissions()->count() <= 0) {
            return false;
        }

        if (!($permissionPlugin instanceof ExecutablePermissionPluginInterface)) {
            return true;
        }

        return $this->executePermissionCollection($permissionPlugin, $permissionCollectionTransfer, $context);
    }

    /**
     * If one of the permission configurations wins, then a subject has the permission
     *
     * @example A junior sales manager could place an order up to 1000 and
     *  a senior sales manager up to 2000. A user has both roles assigned, then he/she has
     *  the permission to place an order up to 2000.
     *
     * @param \Spryker\Shared\PermissionExtension\Dependency\Plugin\ExecutablePermissionPluginInterface $permissionPlugin
     * @param \Generated\Shared\Transfer\PermissionCollectionTransfer $permissionCollectionTransfer
     * @param string|int|array|null $context
     *
     * @return bool
     */
    protected function executePermissionCollection(
        ExecutablePermissionPluginInterface $permissionPlugin,
        PermissionCollectionTransfer $permissionCollectionTransfer,
        $context = null
    ): bool {
        foreach ($permissionCollectionTransfer->getPermissions() as $permissionTransfer) {
            if ($this->executePermission($permissionPlugin, $permissionTransfer, $context)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param \Spryker\Shared\PermissionExtension\Dependency\Plugin\ExecutablePermissionPluginInterface $permissionPlugin
     * @param \Generated\Shared\Transfer\PermissionTransfer $permissionTransfer
     * @param string|int|array|null $context
     *
     * @return bool
     */
    protected function executePermission(ExecutablePermissionPluginInterface $permissionPlugin, PermissionTransfer $permissionTransfer, $context = null): bool
    {
        return $permissionPlugin->can($permissionTransfer->getConfiguration(), $context);
    }

    /**
     * @param string $permissionKey
     *
     * @return \Generated\Shared\Transfer\PermissionCollectionTransfer
     */
    protected function findPermissions($permissionKey): PermissionCollectionTransfer
    {
        $permissionCollectionTransfer = new PermissionCollectionTransfer();

        foreach ($this->permissionStoragePlugins as $permissionStoragePlugin) {
            foreach ($permissionStoragePlugin->getPermissionCollection()->getPermissions() as $permission) {
                if ($permission->getKey() === $permissionKey) {
                    $permissionCollectionTransfer->addPermission($permission);
                }
            }
        }

        return $permissionCollectionTransfer;
    }
}
