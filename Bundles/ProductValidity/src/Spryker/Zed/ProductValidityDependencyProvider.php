<?php

namespace Spryker\Zed\ProductValidity;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\ProductValidity\Dependency\ProductValidityToProductFacadeBridge;

class ProductValidityDependencyProvider extends AbstractBundleDependencyProvider
{
    public const FACADE_PRODUCT = 'FACADE_PRODUCT';

    /**
     * @param Container $container
     *
     * @return Container
     */
    public function provideBusinessLayerDependencies(Container $container)
    {
        $container = parent::provideBusinessLayerDependencies($container);
        $container = $this->addProductFacade($container);

        return $container;
    }

    /**
     * @param Container $container
     *
     * @return Container
     */
    protected function addProductFacade(Container $container)
    {
        $container[static::FACADE_PRODUCT] = function (Container $container) {
            return new ProductValidityToProductFacadeBridge($container->getLocator()->product()->facade());
        };

        return $container;
    }
}