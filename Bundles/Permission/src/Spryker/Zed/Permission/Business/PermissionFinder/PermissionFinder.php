<?php

namespace Spryker\Zed\Permission\Business\PermissionFinder;

use Spryker\Zed\Permission\Communication\Plugin\ExecutablePermissionPluginInterface;
use Spryker\Zed\Permission\Communication\Plugin\PermissionPluginInterface;

class PermissionFinder implements PermissionFinderInterface
{
    /**
     * @var ExecutablePermissionPluginInterface[]
     */
    protected $executablePermissionPluginStack = [];

    /**
     * @param PermissionPluginInterface[] $permissionPlugins
     */
    public function __construct(array $permissionPlugins)
    {
        $this->executablePermissionPluginStack = $this->indexPermissions($permissionPlugins);
    }

    public function findPermissionPlugin($permissionKey)
    {
        if (!isset($this->executablePermissionPluginStack[$permissionKey])) {
            return null;
        }

        return $this->executablePermissionPluginStack[$permissionKey];
    }

    /**
     * @param PermissionPluginInterface[] $permissionPlugins
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