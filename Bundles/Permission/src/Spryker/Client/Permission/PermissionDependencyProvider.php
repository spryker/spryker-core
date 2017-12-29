<?php


namespace Spryker\Client\Permission;


use Spryker\Client\Kernel\AbstractDependencyProvider;
use Spryker\Client\Kernel\Container;
use Spryker\Client\Permission\Plugin\ProductReadPermissionPlugin;

class PermissionDependencyProvider extends AbstractDependencyProvider
{
    const PERMISSION_PLUGINS = 'PERMISSION_PLUGINS';

    public function provideServiceLayerDependencies(Container $container)
    {
        $container = parent::provideServiceLayerDependencies($container);
        $container = $this->addPermissionPlugins($container);

        return $container;
    }

    /**
     * @param Container $container
     *
     * @return Container
     */
    protected function addPermissionPlugins(Container $container)
    {
        $container[static::PERMISSION_PLUGINS] = function (Container $container) {
            return $this->getPermissionPlugins();
        };

        return $container;
    }

    /**
     * @return array
     */
    protected function getPermissionPlugins()
    {
        return [
            new ProductReadPermissionPlugin()
        ];
    }
}