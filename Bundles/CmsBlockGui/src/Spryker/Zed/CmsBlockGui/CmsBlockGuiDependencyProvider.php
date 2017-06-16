<?php


namespace Spryker\Zed\CmsBlockGui;


use Spryker\Zed\CmsBlockCategoryConnector\Communication\Plugin\CmsBlockCategoryFormPlugin;
use Spryker\Zed\CmsBlockGui\Dependency\Facade\CmsBlockGuiToCmsBlockBridge;
use Spryker\Zed\CmsBlockGui\Dependency\Facade\CmsBlockGuiToLocaleBridge;
use Spryker\Zed\CmsBlockGui\Dependency\QueryContainer\CmsBlockGuiToCmsBlockQueryContainerBridge;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;

class CmsBlockGuiDependencyProvider extends AbstractBundleDependencyProvider
{
    const FACADE_CMS_BLOCK = 'CMS_BLOCK_GUI:FACADE_CMS_BLOCK';
    const FACADE_LOCALE = 'CMS_BLOCK_GUI:FACADE_LOCALE';

    const QUERY_CONTAINER_CMS_BLOCK = 'CMS_BLOCK_GUI:QUERY_CONTAINER_CMS_BLOCK';

    const PLUGINS_CMS_BLOCK_FORM = 'CMS_BLOCK_GUI:PLUGINS_CMS_BLOCK_FORM';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideCommunicationLayerDependencies(Container $container)
    {
        $container = parent::provideCommunicationLayerDependencies($container);
        $container = $this->addCmsBlockQueryContainer($container);
        $container = $this->addCmsBlockFacade($container);
        $container = $this->addLocaleFacade($container);
        $container = $this->addCmsBlockFormPlugins($container);

        return $container;
    }

    /**
     * @param Container $container
     *
     * @return Container
     */
    protected function addCmsBlockQueryContainer(Container $container)
    {
        $container[static::QUERY_CONTAINER_CMS_BLOCK] = function (Container $container) {
            return new CmsBlockGuiToCmsBlockQueryContainerBridge($container->getLocator()->cmsBlock()->queryContainer());
        };

        return $container;
    }

    /**
     * @param Container $container
     *
     * @return Container
     */
    protected function addCmsBlockFacade(Container $container)
    {
        $container[static::FACADE_CMS_BLOCK] = function (Container $container) {
            return new CmsBlockGuiToCmsBlockBridge($container->getLocator()->cmsBlock()->facade());
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
            return new CmsBlockGuiToLocaleBridge($container->getLocator()->locale()->facade());
        };

        return $container;
    }

    /**
     * @param Container $container
     *
     * @return Container
     */
    protected function addCmsBlockFormPlugins(Container $container)
    {
        $container[static::PLUGINS_CMS_BLOCK_FORM] = function (Container $container) {
            return $this->getCmsBlockFormPlugins();
        };

        return $container;
    }

    /**
     * ??? Remove all in the end ???
     *
     * @return array
     */
    protected function getCmsBlockFormPlugins()
    {
        return [
            new CmsBlockCategoryFormPlugin()
        ];
    }
}