<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Cms;

use Orm\Zed\Locale\Persistence\SpyLocaleQuery;
use Spryker\Zed\Cms\Dependency\Facade\CmsToEventFacadeBridge;
use Spryker\Zed\Cms\Dependency\Facade\CmsToGlossaryFacadeBridge;
use Spryker\Zed\Cms\Dependency\Facade\CmsToLocaleFacadeBridge;
use Spryker\Zed\Cms\Dependency\Facade\CmsToMessageBrokerFacadeBridge;
use Spryker\Zed\Cms\Dependency\Facade\CmsToTouchFacadeBridge;
use Spryker\Zed\Cms\Dependency\Facade\CmsToUrlFacadeBridge;
use Spryker\Zed\Cms\Dependency\Service\CmsToUtilEncodingBridge;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;

/**
 * @method \Spryker\Zed\Cms\CmsConfig getConfig()
 */
class CmsDependencyProvider extends AbstractBundleDependencyProvider
{
    /**
     * @var string
     */
    public const FACADE_URL = 'FACADE_URL';

    /**
     * @var string
     */
    public const FACADE_LOCALE = 'FACADE_LOCALE';

    /**
     * @var string
     */
    public const FACADE_GLOSSARY = 'FACADE_GLOSSARY';

    /**
     * @var string
     */
    public const FACADE_TOUCH = 'FACADE_TOUCH';

    /**
     * @var string
     */
    public const FACADE_MESSAGE_BROKER = 'FACADE_MESSAGE_BROKER';

    /**
     * @var string
     */
    public const FACADE_EVENT = 'FACADE_EVENT';

    /**
     * @var string
     */
    public const QUERY_CONTAINER_URL = 'QUERY_CONTAINER_URL';

    /**
     * @var string
     */
    public const QUERY_CONTAINER_GLOSSARY = 'QUERY_CONTAINER_GLOSSARY';

    /**
     * @var string
     */
    public const QUERY_CONTAINER_CATEGORY = 'QUERY_CONTAINER_CATEGORY';

    /**
     * @var string
     */
    public const PROPEL_QUERY_LOCALE = 'PROPEL_QUERY_LOCALE';

    /**
     * @var string
     */
    public const PLUGINS_CMS_VERSION_POST_SAVE_PLUGINS = 'PLUGINS_CMS_VERSION_POST_SAVE_PLUGINS';

    /**
     * @var string
     */
    public const PLUGINS_CMS_VERSION_TRANSFER_EXPANDER_PLUGINS = 'PLUGINS_CMS_VERSION_TRANSFER_EXPANDER_PLUGINS';

    /**
     * @var string
     */
    public const PLUGINS_CMS_PAGE_DATA_EXPANDER = 'PLUGINS_CMS_PAGE_DATA_EXPANDER';

    /**
     * @var string
     */
    public const PLUGINS_CMS_PAGE_POST_ACTIVATOR = 'PLUGINS_CMS_PAGE_POST_ACTIVATOR';

    /**
     * @var string
     */
    public const PLUGINS_CMS_PAGE_BEFORE_DELETE = 'PLUGINS_CMS_PAGE_BEFORE_DELETE';

    /**
     * @var string
     */
    public const SERVICE_UTIL_ENCODING = 'SERVICE_UTIL_ENCODING';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideCommunicationLayerDependencies(Container $container): Container
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
    public function provideBusinessLayerDependencies(Container $container): Container
    {
        $this->addTouchFacade($container);
        $this->addGlossaryFacade($container);
        $this->addUrlFacade($container);
        $this->addLocaleFacade($container);
        $this->addCmsVersionPostSavePlugins($container);
        $this->addCmsVersionTransferExpanderPlugins($container);
        $this->addCmsPagePostActivatorPlugins($container);
        $this->addUtilEncodingService($container);
        $this->addCmsPageDataExpanderPlugins($container);
        $this->addCmsPageBeforeDeletePlugins($container);
        $this->addEventFacade($container);
        $this->addMessageBrokerFacade($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function providePersistenceLayerDependencies(Container $container): Container
    {
        $this->addUrlQueryContainer($container);
        $this->addGlossaryQueryContainer($container);
        $this->addCategoryQueryContainer($container);
        $this->addLocalePropelQuery($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return void
     */
    protected function addUrlFacade(Container $container): void
    {
        $container->set(static::FACADE_URL, function (Container $container) {
            return new CmsToUrlFacadeBridge($container->getLocator()->url()->facade());
        });
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return void
     */
    protected function addLocaleFacade(Container $container): void
    {
        $container->set(static::FACADE_LOCALE, function (Container $container) {
            return new CmsToLocaleFacadeBridge($container->getLocator()->locale()->facade());
        });
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return void
     */
    protected function addGlossaryFacade(Container $container): void
    {
        $container->set(static::FACADE_GLOSSARY, function (Container $container) {
            return new CmsToGlossaryFacadeBridge($container->getLocator()->glossary()->facade());
        });
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return void
     */
    protected function addTouchFacade(Container $container): void
    {
        $container->set(static::FACADE_TOUCH, function (Container $container) {
            return new CmsToTouchFacadeBridge($container->getLocator()->touch()->facade());
        });
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return void
     */
    protected function addMessageBrokerFacade(Container $container): void
    {
        $container->set(static::FACADE_MESSAGE_BROKER, function (Container $container) {
            return new CmsToMessageBrokerFacadeBridge($container->getLocator()->messageBroker()->facade());
        });
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return void
     */
    protected function addUtilEncodingService(Container $container): void
    {
        $container->set(static::SERVICE_UTIL_ENCODING, function (Container $container) {
            return new CmsToUtilEncodingBridge($container->getLocator()->utilEncoding()->service());
        });
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return void
     */
    protected function addCmsVersionPostSavePlugins(Container $container): void
    {
        $container->set(static::PLUGINS_CMS_VERSION_POST_SAVE_PLUGINS, function (Container $container) {
            return $this->getPostSavePlugins($container);
        });
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return void
     */
    protected function addCmsVersionTransferExpanderPlugins(Container $container): void
    {
        $container->set(static::PLUGINS_CMS_VERSION_TRANSFER_EXPANDER_PLUGINS, function (Container $container) {
            return $this->getTransferExpanderPlugins($container);
        });
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return void
     */
    protected function addCmsPagePostActivatorPlugins(Container $container): void
    {
        $container->set(static::PLUGINS_CMS_PAGE_POST_ACTIVATOR, function (Container $container) {
            return $this->getCmsPagePostActivatorPlugins();
        });
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return void
     */
    protected function addCmsPageBeforeDeletePlugins(Container $container): void
    {
        $container->set(static::PLUGINS_CMS_PAGE_BEFORE_DELETE, function (Container $container) {
            return $this->getCmsPageBeforeDeletePlugins();
        });
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return void
     */
    protected function addCmsPageDataExpanderPlugins(Container $container): void
    {
        $container->set(static::PLUGINS_CMS_PAGE_DATA_EXPANDER, function (Container $container) {
            return $this->getCmsPageDataExpanderPlugins();
        });
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return array<\Spryker\Zed\CmsExtension\Dependency\Plugin\CmsVersionPostSavePluginInterface>
     */
    protected function getPostSavePlugins(Container $container): array
    {
        return [];
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return array<\Spryker\Zed\CmsExtension\Dependency\Plugin\CmsVersionTransferExpanderPluginInterface>
     */
    protected function getTransferExpanderPlugins(Container $container): array
    {
        return [];
    }

    /**
     * @return array<\Spryker\Zed\CmsExtension\Dependency\Plugin\CmsPageDataExpanderPluginInterface>
     */
    protected function getCmsPageDataExpanderPlugins(): array
    {
        return [];
    }

    /**
     * @return array<\Spryker\Zed\Cms\Communication\Plugin\PostCmsPageActivatorPluginInterface>
     */
    protected function getCmsPagePostActivatorPlugins(): array
    {
        return [];
    }

    /**
     * @return array<\Spryker\Zed\CmsExtension\Dependency\Plugin\CmsPageBeforeDeletePluginInterface>
     */
    protected function getCmsPageBeforeDeletePlugins(): array
    {
        return [];
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return void
     */
    protected function addLocalePropelQuery(Container $container): void
    {
        $container->set(static::PROPEL_QUERY_LOCALE, $container->factory(function (Container $container) {
            return SpyLocaleQuery::create();
        }));
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return void
     */
    protected function addCategoryQueryContainer(Container $container): void
    {
        $container->set(static::QUERY_CONTAINER_CATEGORY, function (Container $container) {
            return $container->getLocator()->category()->queryContainer();
        });
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return void
     */
    protected function addUrlQueryContainer(Container $container): void
    {
        $container->set(static::QUERY_CONTAINER_URL, function (Container $container) {
            return $container->getLocator()->url()->queryContainer();
        });
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return void
     */
    protected function addGlossaryQueryContainer(Container $container): void
    {
        $container->set(static::QUERY_CONTAINER_GLOSSARY, function (Container $container) {
            return $container->getLocator()->glossary()->queryContainer();
        });
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return void
     */
    protected function addEventFacade(Container $container): void
    {
        $container->set(static::FACADE_EVENT, function (Container $container) {
            return new CmsToEventFacadeBridge($container->getLocator()->event()->facade());
        });
    }
}
