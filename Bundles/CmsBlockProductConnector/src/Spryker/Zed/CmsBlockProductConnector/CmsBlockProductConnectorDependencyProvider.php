<?php


namespace Spryker\Zed\CmsBlockProductConnector;


use Spryker\Zed\CmsBlockProductConnector\Dependency\Facade\LocaleFacadeBridge;
use Spryker\Zed\CmsBlockProductConnector\Dependency\QueryContainer\ProductAbstractQueryContainerBridge;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;

class CmsBlockProductConnectorDependencyProvider extends AbstractBundleDependencyProvider
{
    const FACADE_LOCALE = 'CMS_BLOCK_PRODUCT_CONNECTOR:FACADE_LOCALE';

    const QUERY_CONTAINER_PRODUCT_ABSTRACT = 'CMS_BLOCK_PRODUCT_CONNECTOR:QUERY_CONTAINER_PRODUCT_ABSTRACT';

    /**
     * @param Container $container
     *
     * @return Container
     */
    public function provideCommunicationLayerDependencies(Container $container)
    {
        $container = parent::provideCommunicationLayerDependencies($container);
        $container = $this->addProductAbstractQueryContainer($container);
        $container = $this->addLocaleFacade($container);

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
    protected function addProductAbstractQueryContainer(Container $container)
    {
        $container[static::QUERY_CONTAINER_PRODUCT_ABSTRACT] = function (Container $container) {
            return new ProductAbstractQueryContainerBridge($container->getLocator()->product()->queryContainer());
        };

        return $container;
    }

}