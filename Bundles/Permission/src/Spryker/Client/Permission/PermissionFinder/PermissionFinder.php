<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Permission\PermissionFinder;

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
