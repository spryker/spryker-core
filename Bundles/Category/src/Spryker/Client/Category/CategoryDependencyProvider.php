<?php


namespace Spryker\Client\Category;


use Spryker\Client\Category\Dependency\Client\CategoryToStorageClientBridge;
use Spryker\Client\Kernel\AbstractDependencyProvider;
use Spryker\Client\Kernel\Container;

class CategoryDependencyProvider extends AbstractDependencyProvider
{
    const CLIENT_STORAGE = 'CLIENT_STORAGE';

    /**
     * @param Container $container
     *
     * @return Container
     */
    public function provideServiceLayerDependencies(Container $container)
    {
        $container = parent::provideServiceLayerDependencies($container);
        $container = $this->addStorage($container);

        return $container;
    }

    /**
     * @param Container $container
     *
     * @return Container
     */
    protected function addStorage(Container $container)
    {
        $container[static::CLIENT_STORAGE] = function (Container $container) {
            return new CategoryToStorageClientBridge($container->getLocator()->storage()->client());
        };

        return $container;
    }

}