<?php


namespace Spryker\Zed\CmsBlock;


use Spryker\Zed\CmsBlock\Dependency\Facade\CmsBlockToTouchFacadeBridge;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;

class CmsBlockDependencyProvider extends AbstractBundleDependencyProvider
{

    const FACADE_TOUCH = 'FACADE_TOUCH';

    /**
     * @param Container $container
     *
     * @return Container
     */
    public function provideBusinessLayerDependencies(Container $container)
    {
        $container = parent::provideBusinessLayerDependencies($container);
        $container = $this->addTouchFacade($container);

        return $container;
    }

    /**
     * @param Container $container
     *
     * @return Container
     */
    protected function addTouchFacade(Container $container)
    {
        $container[static::FACADE_TOUCH] = function (Container $container) {
            return new CmsBlockToTouchFacadeBridge($container->getLocator()->touch()->facade());
        };

        return $container;
    }
}