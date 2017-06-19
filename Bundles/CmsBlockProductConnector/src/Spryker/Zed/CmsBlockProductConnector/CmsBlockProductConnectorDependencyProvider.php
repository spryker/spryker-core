<?php


namespace Spryker\Zed\CmsBlockProductConnector;


use Spryker\Zed\CmsBlockProductConnector\Dependency\QueryContainer\ProductAbstractQueryContainerBridge;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;

class CmsBlockProductConnectorDependencyProvider extends AbstractBundleDependencyProvider
{
    const QUERY_CONTAINER_PRODUCT_ABSTRACT = 'CMS_BLOCK_PRODUCT_CONNECTOR:QUERY_CONTAINER_PRODUCT_ABSTRACT';

    /**
     * @param Container $container
     *
     * @return Container
     */
    public function provideCommunicationLayerDependencies(Container $container)
    {
        $container = parent::provideCommunicationLayerDependencies($container);
        $container = $this->addProductAbstracQueryContainer($container);

        return $container;
    }

    /**
     * @param Container $container
     *
     * @return Container
     */
    protected function addProductAbstracQueryContainer(Container $container)
    {
        $container[static::QUERY_CONTAINER_PRODUCT_ABSTRACT] = function (Container $container) {
            return new ProductAbstractQueryContainerBridge($container->getLocator()->product()->queryContainer());
        };

        return $container;
    }

}