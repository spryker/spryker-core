<?php


namespace Spryker\Client\Rbac;


use Spryker\Client\Kernel\AbstractDependencyProvider;
use Spryker\Client\Kernel\Container;
use Spryker\Client\Rbac\Plugin\ProductReadRightPlugin;

class RbacDependencyProvider extends AbstractDependencyProvider
{
    const RIGHTS = 'RIGHTS';

    public function provideServiceLayerDependencies(Container $container)
    {
        $container = parent::provideServiceLayerDependencies($container);
        $container = $this->addRights($container);

        return $container;
    }

    /**
     * @param Container $container
     *
     * @return Container
     */
    protected function addRights(Container $container)
    {
        $container[static::RIGHTS] = function (Container $container) {
            return $this->getRights();
        };

        return $container;
    }

    /**
     * @return array
     */
    protected function getRights()
    {
        return [
            new ProductReadRightPlugin()
        ];
    }
}