<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Permission\Business\PermissionFinder;

use Spryker\Zed\Permission\Communication\Plugin\ExecutablePermissionPluginInterface;

class PermissionFinder implements PermissionFinderInterface
{
    /**
     * @var \Spryker\Zed\Permission\Communication\Plugin\ExecutablePermissionPluginInterface[]
     */
    protected $executablePermissionPluginStack = [];

    /**
     * @param \Spryker\Zed\Permission\Communication\Plugin\PermissionPluginInterface[] $permissionPlugins
     */
    public function __construct(array $permissionPlugins)
    {
        $this->executablePermissionPluginStack = $this->indexPermissions($permissionPlugins);
    }

    /**
     * @param string $permissionKey
     *
     * @return null|\Spryker\Zed\Permission\Communication\Plugin\ExecutablePermissionPluginInterface
     */
    public function findPermissionPlugin($permissionKey)
    {
        if (!isset($this->executablePermissionPluginStack[$permissionKey])) {
            return null;
        }

        return $this->executablePermissionPluginStack[$permissionKey];
    }

    /**
     * @param \Spryker\Zed\Permission\Communication\Plugin\PermissionPluginInterface[] $permissionPlugins
     *
     * @return array
     */
    protected function indexPermissions(array $permissionPlugins)
    {
        $plugins = [];

        foreach ($permissionPlugins as $permissionPlugin) {
            if ($permissionPlugin instanceof ExecutablePermissionPluginInterface) {
                $plugins[$permissionPlugin->getKey()] = $permissionPlugin;
            }
        }

        return $plugins;
    }
}
