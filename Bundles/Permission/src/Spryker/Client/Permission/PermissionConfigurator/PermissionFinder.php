<?php

namespace Spryker\Client\Permission\PermissionFinder;

use Generated\Shared\Transfer\PermissionTransfer;
use Spryker\Client\Permission\Plugin\ExecutablePermissionPluginInterface;
use Spryker\Client\Permission\Plugin\PermissionPluginInterface;

class PermissionFinder implements PermissionFinderInterface
{
    /**
     * @var ExecutablePermissionPluginInterface[]
     */
    protected $executablePermissionPlugins;

    /**
     * @param ExecutablePermissionPluginInterface[] $permissionPlugins
     */
    public function __construct(array $permissionPlugins)
    {
        $this->executablePermissionPlugins = $this->indexPermissions($permissionPlugins);
    }

    /**
     * @param string $permissionKey
     *
     * @throws \Exception
     *
     * @return ExecutablePermissionPluginInterface
     */
    public function getPermissionPlugin($permissionKey)
    {
        if (!isset($this->executablePermissionPlugins[$permissionKey])) {
            throw new \Exception('Permission is not registered');
        }

        return $this->executablePermissionPlugins[$permissionKey];
    }

    /**
     * @param PermissionPluginInterface[] $permissionPlugins
     *
     * @return ExecutablePermissionPluginInterface[]
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