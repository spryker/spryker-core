<?php


namespace Spryker\Zed\CmsBlock;


use Spryker\Zed\CmsBlock\Dependency\Facade\CmsBlockToGlossaryFacadeBridge;
use Spryker\Zed\CmsBlock\Dependency\Facade\CmsBlockToLocaleFacadeBridge;
use Spryker\Zed\CmsBlock\Dependency\Facade\CmsBlockToTouchFacadeBridge;
use Spryker\Zed\CmsBlock\Dependency\QueryContainer\CmsBlockToGlossaryQueryContainerBridge;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;

class CmsBlockDependencyProvider extends AbstractBundleDependencyProvider
{

    const FACADE_TOUCH = 'FACADE_TOUCH';
    const FACADE_GLOSSARY = 'FACADE_GLOSSARY';
    const FACADE_LOCALE = 'FACADE_LOCALE';

    const QUERY_CONTAINER_GLOSSARY = 'QUERY_CONTAINER_GLOSSARY';

    /**
     * @param Container $container
     *
     * @return Container
     */
    public function provideBusinessLayerDependencies(Container $container)
    {
        $container = parent::provideBusinessLayerDependencies($container);
        $container = $this->addTouchFacade($container);
        $container = $this->addGlossaryFacade($container);
        $container = $this->addLocaleFacade($container);
        $container = $this->addGlossaryQueryContainer($container);

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

    /**
     * @param Container $container
     *
     * @return Container
     */
    protected function addGlossaryFacade(Container $container)
    {
        $container[static::FACADE_GLOSSARY] = function (Container $container) {
            return new CmsBlockToGlossaryFacadeBridge($container->getLocator()->glossary()->facade());
        };

        return $container;
    }

    /**
     * @param Container $container
     *
     * @return Container
     */
    protected function addLocaleFacade(Container $container)
    {
        $container[static::FACADE_LOCALE] = function (Container $container) {
            return new CmsBlockToLocaleFacadeBridge($container->getLocator()->locale()->facade());
        };

        return $container;
    }

    /**
     * @param Container $container
     *
     * @return Container
     */
    protected function addGlossaryQueryContainer(Container $container)
    {
        $container[static::QUERY_CONTAINER_GLOSSARY] = function (Container $container) {
            return new CmsBlockToGlossaryQueryContainerBridge($container->getLocator()->glossary()->queryContainer());
        };

        return $container;
    }
}