<?php

namespace Spryker\Zed\CmsBlockCategoryConnector;

use Spryker\Zed\CmsBlockCategoryConnector\Dependency\Facade\CollectorFacadeBridge;
use Spryker\Zed\CmsBlockCategoryConnector\Dependency\Facade\LocaleFacadeBridge;
use Spryker\Zed\CmsBlockCategoryConnector\Dependency\Facade\TouchFacadeBridge;
use Spryker\Zed\CmsBlockCategoryConnector\Dependency\QueryContainer\CategoryQueryContainerBridge;
use Spryker\Zed\CmsBlockCategoryConnector\Dependency\QueryContainer\TouchQueryContainerBridge;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;

class CmsBlockCategoryConnectorDependencyProvider extends AbstractBundleDependencyProvider
{

    const FACADE_LOCALE = 'CMS_BLOCK_CATEGORY_CONNECTOR:FACADE_LOCALE';
    const FACADE_TOUCH = 'CMS_BLOCK_CATEGORY_CONNECTOR:FACADE_TOUCH';
    const FACADE_COLLECTOR = 'CMS_BLOCK_CATEGORY_CONNECTOR:FACADE_COLLECTOR';

    const QUERY_CONTAINER_CATEGORY = 'CMS_BLOCK_CATEGORY_CONNECTOR:QUERY_CONTAINER_CATEGORY';
    const QUERY_CONTAINER_TOUCH = 'CMS_BLOCK_CATEGORY_CONNECTOR:QUERY_CONTAINER_TOUCH';

    const SERVICE_DATA_READER = 'CMS_BLOCK_CATEGORY_CONNECTOR:SERVICE_DATA_READER';

    /**
     * @param Container $container
     *
     * @return Container
     */
    public function provideCommunicationLayerDependencies(Container $container)
    {
        $container = parent::provideCommunicationLayerDependencies($container);
        $container = $this->addLocaleFacade($container);
        $container = $this->addTouchFacade($container);
        $container = $this->addCategoryQueryContainer($container);

        return $container;
    }

    /**
     * @param Container $container
     *
     * @return Container
     */
    public function provideBusinessLayerDependencies(Container $container)
    {
        $container = parent::provideBusinessLayerDependencies($container);
        $container = $this->addCollectorFacade($container);
        $container = $this->addDataReaderService($container);
        $container = $this->addTouchQueryContainer($container);

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
            return new LocaleFacadeBridge($container->getLocator()->locale()->facade());
        };

        return $container;
    }

    /**
     * @param Container $container
     */
    protected function addTouchFacade(Container $container)
    {
        $container[static::FACADE_TOUCH] = function (Container $container) {
            return new TouchFacadeBridge($container->getLocator()->touch()->facade());
        };
    }

    /**
     * @param Container $container
     *
     * @return Container
     */
    protected function addCategoryQueryContainer(Container $container)
    {
        $container[static::QUERY_CONTAINER_CATEGORY] = function (Container $container) {
            return new CategoryQueryContainerBridge($container->getLocator()->category()->queryContainer());
        };

        return $container;
    }

    /**
     * @param Container $container
     *
     * @return Container
     */
    protected function addCollectorFacade(Container $container)
    {
        $container[self::FACADE_COLLECTOR] = function (Container $container) {
            return new CollectorFacadeBridge($container->getLocator()->collector()->facade());
        };

        return $container;
    }

    /**
     * @param Container $container
     *
     * @return Container
     */
    protected function addDataReaderService(Container $container)
    {
        $container[self::SERVICE_DATA_READER] = function (Container $container) {
            return $container->getLocator()->utilDataReader()->service();
        };

        return $container;
    }

    /**
     * @param Container $container
     *
     * @return Container
     */
    protected function addTouchQueryContainer(Container $container)
    {
        $container[static::QUERY_CONTAINER_TOUCH] = function (Container $container) {
            return new TouchQueryContainerBridge($container->getLocator()->touch()->queryContainer());
        };

        return $container;
    }

}