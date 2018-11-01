<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsBlock;

use Spryker\Zed\CmsBlock\Dependency\Facade\CmsBlockToEventFacadeBridge;
use Spryker\Zed\CmsBlock\Dependency\Facade\CmsBlockToGlossaryBridge;
use Spryker\Zed\CmsBlock\Dependency\Facade\CmsBlockToLocaleBridge;
use Spryker\Zed\CmsBlock\Dependency\Facade\CmsBlockToTouchBridge;
use Spryker\Zed\CmsBlock\Dependency\QueryContainer\CmsBlockToGlossaryQueryContainerBridge;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;

class CmsBlockDependencyProvider extends AbstractBundleDependencyProvider
{
    public const FACADE_TOUCH = 'FACADE_TOUCH';
    public const FACADE_EVENT = 'FACADE_EVENT';
    public const FACADE_GLOSSARY = 'FACADE_GLOSSARY';
    public const FACADE_LOCALE = 'FACADE_LOCALE';

    public const QUERY_CONTAINER_GLOSSARY = 'QUERY_CONTAINER_GLOSSARY';

    public const PLUGIN_CMS_BLOCK_UPDATE = 'CMS_BLOCK:PLUGIN_CMS_BLOCK_UPDATE';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container)
    {
        $container = parent::provideBusinessLayerDependencies($container);
        $container = $this->addTouchFacade($container);
        $container = $this->addEventFacade($container);
        $container = $this->addGlossaryFacade($container);
        $container = $this->addLocaleFacade($container);
        $container = $this->addGlossaryQueryContainer($container);
        $container = $this->addCmsBlockUpdatePlugins($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addTouchFacade(Container $container)
    {
        $container[static::FACADE_TOUCH] = function (Container $container) {
            return new CmsBlockToTouchBridge($container->getLocator()->touch()->facade());
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addEventFacade(Container $container): Container
    {
        $container[static::FACADE_EVENT] = function (Container $container) {
            return new CmsBlockToEventFacadeBridge($container->getLocator()->event()->facade());
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addGlossaryFacade(Container $container)
    {
        $container[static::FACADE_GLOSSARY] = function (Container $container) {
            return new CmsBlockToGlossaryBridge($container->getLocator()->glossary()->facade());
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addLocaleFacade(Container $container)
    {
        $container[static::FACADE_LOCALE] = function (Container $container) {
            return new CmsBlockToLocaleBridge($container->getLocator()->locale()->facade());
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addGlossaryQueryContainer(Container $container)
    {
        $container[static::QUERY_CONTAINER_GLOSSARY] = function (Container $container) {
            return new CmsBlockToGlossaryQueryContainerBridge($container->getLocator()->glossary()->queryContainer());
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addCmsBlockUpdatePlugins(Container $container)
    {
        $container[static::PLUGIN_CMS_BLOCK_UPDATE] = function (Container $container) {
            return $this->getCmsBlockUpdatePlugins();
        };

        return $container;
    }

    /**
     * @return array
     */
    protected function getCmsBlockUpdatePlugins()
    {
        return [];
    }
}
