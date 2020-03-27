<?php


namespace Spryker\Zed\IndexGenerator;


use Spryker\Zed\IndexGenerator\Dependency\Facade\IndexGeneratorToPropelFacadeBridge;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;

class IndexGeneratorDependencyProvider extends AbstractBundleDependencyProvider
{
    public const FACADE_PROPEL = 'FACADE_PROPEL';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container): Container
    {
        $container = parent::provideBusinessLayerDependencies($container);
        $container = $this->addPropelFacade($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addPropelFacade(Container $container): Container
    {
        $container->set(static::FACADE_PROPEL, function (Container $container) {
            return new IndexGeneratorToPropelFacadeBridge(
                $container->getLocator()->propel()->facade()
            );
        });

        return $container;
    }
}
