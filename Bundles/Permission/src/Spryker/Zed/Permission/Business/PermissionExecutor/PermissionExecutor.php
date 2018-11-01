<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Permission\Business\PermissionExecutor;

use Generated\Shared\Transfer\PermissionCollectionTransfer;
use Generated\Shared\Transfer\PermissionTransfer;
use Spryker\Shared\PermissionExtension\Dependency\Plugin\ExecutablePermissionPluginInterface;
use Spryker\Zed\Permission\Business\PermissionFinder\PermissionFinderInterface;

class PermissionExecutor implements PermissionExecutorInterface
{
    /**
     * @var \Spryker\Zed\PermissionExtension\Dependency\Plugin\PermissionStoragePluginInterface[]
     */
    protected $permissionStoragePlugins;

    /**
     * @var \Spryker\Zed\Permission\Business\PermissionFinder\PermissionFinderInterface
     */
    protected $permissionFinder;

    /**
     * @param \Spryker\Zed\PermissionExtension\Dependency\Plugin\PermissionStoragePluginInterface[] $permissionStoragePlugins
     * @param \Spryker\Zed\Permission\Business\PermissionFinder\PermissionFinderInterface $permissionFinder
     */
    public function __construct(
        array $permissionStoragePlugins,
        PermissionFinderInterface $permissionFinder
    ) {
        $this->permissionStoragePlugins = $permissionStoragePlugins;
        $this->permissionFinder = $permissionFinder;
    }

    /**
     * @param string $permissionKey
     * @param int|string $identifier
     * @param string|int|array|null $context
     *
     * @return bool
     */
    public function can($permissionKey, $identifier, $context = null): bool
    {
        $permissionPlugin = $this->permissionFinder->findPermissionPlugin($permissionKey);

        if (!$permissionPlugin) {
            return true;
        }

        $permissionCollectionTransfer = $this->findPermissions($permissionKey, $identifier);

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
    protected function executePermission(
        ExecutablePermissionPluginInterface $permissionPlugin,
        PermissionTransfer $permissionTransfer,
        $context = null
    ): bool {
        return $permissionPlugin->can($permissionTransfer->getConfiguration(), $context);
    }

    /**
     * @param string $permissionKey
     * @param int|string $identifier
     *
     * @return \Generated\Shared\Transfer\PermissionCollectionTransfer
     */
    protected function findPermissions(string $permissionKey, $identifier): PermissionCollectionTransfer
    {
        $permissionCollectionTransfer = new PermissionCollectionTransfer();

        foreach ($this->permissionStoragePlugins as $permissionStoragePlugin) {
            foreach ($permissionStoragePlugin->getPermissionCollection($identifier)->getPermissions() as $permission) {
                if ($permission->getKey() === $permissionKey) {
                    $permissionCollectionTransfer->addPermission($permission);
                }
            }
        }

        return $permissionCollectionTransfer;
    }
}
