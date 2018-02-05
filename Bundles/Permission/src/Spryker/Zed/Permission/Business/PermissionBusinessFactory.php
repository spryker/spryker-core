<?php


namespace Spryker\Zed\Permission\Business;


use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\Permission\Business\PermissionExecutor\PermissionExecutor;
use Spryker\Zed\Permission\Business\PermissionExecutor\PermissionExecutorInterface;
use Spryker\Zed\Permission\Business\PermissionFinder\PermissionFinder;
use Spryker\Zed\Permission\Business\PermissionFinder\PermissionFinderInterface;
use Spryker\Zed\Permission\Communication\Plugin\PermissionPluginInterface;
use Spryker\Zed\Permission\Communication\Plugin\PermissionStoragePluginInterface;
use Spryker\Zed\Permission\PermissionDependencyProvider;

class PermissionBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return PermissionStoragePluginInterface
     */
    public function getPermissionStoragePlugin()
    {
        return $this->getProvidedDependency(PermissionDependencyProvider::PLUGIN_PERMISSION_STORAGE);
    }

    /**
     * @return PermissionExecutorInterface
     */
    public function createPermissionExecutor()
    {
        return new PermissionExecutor(
            $this->getPermissionStoragePlugin(),
            $this->createPermissionFinder()
        );
    }

    /**
     * @return PermissionFinderInterface
     */
    public function createPermissionFinder()
    {
        return new PermissionFinder(
            $this->getPermissionPlugins()
        );
    }

    /**
     * @return PermissionPluginInterface[]
     */
    public function getPermissionPlugins()
    {
        return $this->getProvidedDependency(PermissionDependencyProvider::PLUGINS_PERMISSION);
    }
}