<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Cms;

use Propel\Runtime\Propel;
use Spryker\Zed\Cms\Dependency\Facade\CmsToGlossaryBridge;
use Spryker\Zed\Cms\Dependency\Facade\CmsToLocaleBridge;
use Spryker\Zed\Cms\Dependency\Facade\CmsToTouchBridge;
use Spryker\Zed\Cms\Dependency\Facade\CmsToUrlBridge;
use Spryker\Zed\Cms\Dependency\Service\CmsToUtilEncodingBridge;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;

class CmsDependencyProvider extends AbstractBundleDependencyProvider
{
    public const FACADE_URL = 'facade_url';
    public const FACADE_LOCALE = 'facade_locale';
    public const FACADE_GLOSSARY = 'facade glossary';
    public const FACADE_TOUCH = 'facade_touch';
    public const QUERY_CONTAINER_URL = 'url_query_container';
    public const QUERY_CONTAINER_GLOSSARY = 'glossary_query_container';
    public const QUERY_CONTAINER_CATEGORY = 'category query container';
    public const QUERY_CONTAINER_LOCALE = 'locale query container';

    public const PLUGIN_PROPEL_CONNECTION = 'propel connection plugin';
    public const PLUGINS_CMS_VERSION_POST_SAVE_PLUGINS = 'cms version post save plugins';
    public const PLUGINS_CMS_VERSION_TRANSFER_EXPANDER_PLUGINS = 'cms version transfer expander plugins';
    public const PLUGINS_CMS_PAGE_DATA_EXPANDER = 'PLUGINS_CMS_PAGE_DATA_EXPANDER';
    public const PLUGINS_CMS_PAGE_POST_ACTIVATOR = 'PLUGINS_CMS_PAGE_POST_ACTIVATOR';

    public const SERVICE_UTIL_ENCODING = 'util encoding service';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideCommunicationLayerDependencies(Container $container)
    {
        $this->addUrlFacade($container);
        $this->addLocaleFacade($container);
        $this->addGlossaryFacade($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container)
    {
        $this->addPropelPluginConnection($container);
        $this->addTouchFacade($container);
        $this->addGlossaryFacade($container);
        $this->addUrlFacade($container);
        $this->addLocaleFacade($container);
        $this->addCmsVersionPostSavePlugins($container);
        $this->addCmsVersionTransferExpanderPlugins($container);
        $this->addCmsPagePostActivatorPlugins($container);
        $this->addUtilEncodingService($container);
        $this->addCmsPageDataExpanderPlugins($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function providePersistenceLayerDependencies(Container $container)
    {
        $this->addUrlQueryContainer($container);
        $this->addGlossaryQueryContainer($container);
        $this->addCategoryQueryContainer($container);
        $this->addLocaleQueryContainer($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return void
     */
    protected function addUrlFacade(Container $container)
    {
        $container[self::FACADE_URL] = function (Container $container) {
            return new CmsToUrlBridge($container->getLocator()->url()->facade());
        };
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return void
     */
    protected function addLocaleFacade(Container $container)
    {
        $container[self::FACADE_LOCALE] = function (Container $container) {
            return new CmsToLocaleBridge($container->getLocator()->locale()->facade());
        };
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return void
     */
    protected function addGlossaryFacade(Container $container)
    {
        $container[self::FACADE_GLOSSARY] = function (Container $container) {
            return new CmsToGlossaryBridge($container->getLocator()->glossary()->facade());
        };
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return void
     */
    protected function addTouchFacade(Container $container)
    {
        $container[self::FACADE_TOUCH] = function (Container $container) {
            return new CmsToTouchBridge($container->getLocator()->touch()->facade());
        };
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return void
     */
    protected function addPropelPluginConnection(Container $container)
    {
        $container[self::PLUGIN_PROPEL_CONNECTION] = function (Container $container) {
            return Propel::getConnection();
        };
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return void
     */
    protected function addUtilEncodingService(Container $container)
    {
        $container[self::SERVICE_UTIL_ENCODING] = function (Container $container) {
            return new CmsToUtilEncodingBridge($container->getLocator()->utilEncoding()->service());
        };
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return void
     */
    protected function addCmsVersionPostSavePlugins(Container $container)
    {
        $container[self::PLUGINS_CMS_VERSION_POST_SAVE_PLUGINS] = function (Container $container) {
            return $this->getPostSavePlugins($container);
        };
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return void
     */
    protected function addCmsVersionTransferExpanderPlugins(Container $container)
    {
        $container[self::PLUGINS_CMS_VERSION_TRANSFER_EXPANDER_PLUGINS] = function (Container $container) {
            return $this->getTransferExpanderPlugins($container);
        };
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return void
     */
    protected function addCmsPagePostActivatorPlugins(Container $container)
    {
        $container[self::PLUGINS_CMS_PAGE_POST_ACTIVATOR] = function (Container $container) {
            return $this->getCmsPagePostActivatorPlugins();
        };
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return void
     */
    protected function addCmsPageDataExpanderPlugins(Container $container)
    {
        $container[static::PLUGINS_CMS_PAGE_DATA_EXPANDER] = function (Container $container) {
            return $this->getCmsPageDataExpanderPlugins();
        };
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Cms\Dependency\Plugin\CmsVersionPostSavePluginInterface[]
     */
    protected function getPostSavePlugins(Container $container)
    {
        return [];
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Cms\Dependency\Plugin\CmsVersionTransferExpanderPluginInterface[]
     */
    protected function getTransferExpanderPlugins(Container $container)
    {
        return [];
    }

    /**
     * @return \Spryker\Zed\Cms\Dependency\Plugin\CmsPageDataExpanderPluginInterface[]
     */
    protected function getCmsPageDataExpanderPlugins()
    {
        return [];
    }

    /**
     * @return \Spryker\Zed\Cms\Communication\Plugin\PostCmsPageActivatorPluginInterface[]
     */
    protected function getCmsPagePostActivatorPlugins()
    {
        return [];
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return void
     */
    protected function addLocaleQueryContainer(Container $container)
    {
        $container[self::QUERY_CONTAINER_LOCALE] = function (Container $container) {
            return $container->getLocator()->locale()->queryContainer();
        };
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return void
     */
    protected function addCategoryQueryContainer(Container $container)
    {
        $container[self::QUERY_CONTAINER_CATEGORY] = function (Container $container) {
            return $container->getLocator()->category()->queryContainer();
        };
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return void
     */
    protected function addUrlQueryContainer(Container $container)
    {
        $container[self::QUERY_CONTAINER_URL] = function (Container $container) {
            return $container->getLocator()->url()->queryContainer();
        };
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return void
     */
    protected function addGlossaryQueryContainer(Container $container)
    {
        $container[self::QUERY_CONTAINER_GLOSSARY] = function (Container $container) {
            return $container->getLocator()->glossary()->queryContainer();
        };
    }
}
