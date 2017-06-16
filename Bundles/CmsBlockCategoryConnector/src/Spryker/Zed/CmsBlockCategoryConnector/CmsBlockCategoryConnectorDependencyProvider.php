<?php

namespace Spryker\Zed\CmsBlockCategoryConnector;

use Spryker\Zed\CmsBlockCategoryConnector\Dependency\Facade\LocaleFacadeBridge;
use Spryker\Zed\CmsBlockCategoryConnector\Dependency\QueryContainer\CategoryQueryContainerBridge;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;

class CmsBlockCategoryConnectorDependencyProvider extends AbstractBundleDependencyProvider
{

    const FACADE_LOCALE = 'CMS_BLOCK_CATEGORY_CONNECTOR:FACADE_LOCALE';

    const QUERY_CONTAINER_CATEGORY = 'CMS_BLOCK_CATEGORY_CONNECTOR:QUERY_CONTAINER_CATEGORY';

    /**
     * @param Container $container
     *
     * @return Container
     */
    public function provideCommunicationLayerDependencies(Container $container)
    {
        $container = parent::provideCommunicationLayerDependencies($container);
        $container = $this->addLocaleFacade($container);
        $container = $this->addCategoryQueryContainer($container);

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
}