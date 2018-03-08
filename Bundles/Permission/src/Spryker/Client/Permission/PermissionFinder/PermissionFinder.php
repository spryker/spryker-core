<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Permission\PermissionFinder;

use Generated\Shared\Transfer\PermissionCollectionTransfer;
use Generated\Shared\Transfer\PermissionTransfer;
use Spryker\Client\Permission\Plugin\ExecutablePermissionPluginInterface;

class PermissionFinder implements PermissionFinderInterface
{
    /**
     * @var \Spryker\Client\Permission\Plugin\ExecutablePermissionPluginInterface[]
     */
    protected $permissionPlugins = [];

    /**
     * @param \Spryker\Client\Permission\Plugin\ExecutablePermissionPluginInterface[] $permissionPlugins
     */
    public function __construct(array $permissionPlugins)
    {
        $this->permissionPlugins = $this->indexPermissions($permissionPlugins);
    }

    /**
     * @param string $permissionKey
     *
     * @return \Spryker\Client\Permission\Plugin\ExecutablePermissionPluginInterface|null
     */
    public function findPermissionPlugin($permissionKey)
    {
        if (!isset($this->permissionPlugins[$permissionKey])) {
            return null;
        }

        return $this->permissionPlugins[$permissionKey];
    }

    /**
     * @return \Generated\Shared\Transfer\PermissionCollectionTransfer
     */
    public function getRegisteredPermissionCollection(): PermissionCollectionTransfer
    {
        $permissionCollectionTransfer = new PermissionCollectionTransfer();

        foreach ($this->permissionPlugins as $permissionPlugin) {
            $permissionTransfer = (new PermissionTransfer())
                ->setKey($permissionPlugin->getKey());

            if ($permissionPlugin instanceof ExecutablePermissionPluginInterface) {
                $permissionTransfer->setConfigurationSignature($permissionPlugin->getConfigurationSignature());
            }

            $permissionCollectionTransfer->addPermission($permissionTransfer);
        }

        return $permissionCollectionTransfer;
    }

    /**
     * @param \Spryker\Client\Permission\Plugin\PermissionPluginInterface[] $permissionPlugins
     *
     * @return \Spryker\Client\Permission\Plugin\ExecutablePermissionPluginInterface[]
     */
    protected function indexPermissions(array $permissionPlugins)
    {
        $plugins = [];
        foreach ($permissionPlugins as $permissionPlugin) {
            $plugins[$permissionPlugin->getKey()] = $permissionPlugin;
        }

        return $plugins;
    }
}
