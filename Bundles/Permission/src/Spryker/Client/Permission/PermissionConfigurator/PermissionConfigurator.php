<?php

namespace Spryker\Client\Permission\PermissionConfigurator;

use Generated\Shared\Transfer\PermissionTransfer;
use Spryker\Client\Permission\Plugin\ExecutionAwarePermissionPluginInterface;
use Spryker\Client\Permission\Plugin\PermissionPluginInterface;

class PermissionConfigurator implements PermissionConfiguratorInterface
{

    protected $executablePermissionPlugins;

    public function __construct(array $permissionPlugins)
    {
        $this->executablePermissionPlugins = $this->getExecutablePermissions($permissionPlugins);
    }

    /**
     * @param string $permissionKey
     *
     * @return bool
     */
    public function isExecutable($permissionKey)
    {
        return isset($this->executablePermissionPlugins[$permissionKey]);
    }


    /**
     * @param PermissionTransfer $permissionTransfer
     *
     * @throws \Exception
     *
     * @return ExecutionAwarePermissionPluginInterface
     */
    public function configurePermission(PermissionTransfer $permissionTransfer)
    {
        if (!isset($this->executablePermissionPlugins[$permissionTransfer->getKey()])) {
            throw new \Exception('Permission is not registered');
        }

        $permissionPlugin = $this->executablePermissionPlugins[$permissionTransfer->getKey()];
        $permissionPlugin->configure($permissionTransfer->getConfig());

        return $permissionPlugin;
    }

    /**
     * @param PermissionPluginInterface[] $permissionPlugins
     *
     * @return ExecutionAwarePermissionPluginInterface[]
     */
    protected function getExecutablePermissions(array $permissionPlugins)
    {
        $plugins = [];
        foreach ($permissionPlugins as $permissionPlugin) {
            if ($permissionPlugin instanceof ExecutionAwarePermissionPluginInterface) {
                $plugins[$permissionPlugin->getKey()] = $permissionPlugin;
            }
        }

        return $plugins;
    }
}