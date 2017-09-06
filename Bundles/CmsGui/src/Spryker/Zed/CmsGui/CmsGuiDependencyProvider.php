<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsGui;

use Spryker\Zed\CmsGui\Dependency\Facade\CmsGuiToCmsBridge;
use Spryker\Zed\CmsGui\Dependency\Facade\CmsGuiToCmsGlossaryFacadeBridge;
use Spryker\Zed\CmsGui\Dependency\Facade\CmsGuiToLocaleBridge;
use Spryker\Zed\CmsGui\Dependency\Facade\CmsGuiToUrlBridge;
use Spryker\Zed\CmsGui\Dependency\QueryContainer\CmsGuiToCmsQueryContainerBridge;
use Spryker\Zed\CmsGui\Dependency\Service\CmsGuiToUtilEncodingBridge;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Communication\Plugin\Pimple;
use Spryker\Zed\Kernel\Container;

class CmsGuiDependencyProvider extends AbstractBundleDependencyProvider
{

    const FACADE_LOCALE = 'locale facade';
    const FACADE_CMS = 'locale cms';
    const FACADE_URL = 'url facade';
    const FACADE_GLOSSARY = 'glossary facade';
    const FACADE_CMS_CONTENT_WIDGET = 'content widget facade';

    const QUERY_CONTAINER_CMS = 'cms query container';

    const SERVICE_UTIL_ENCODING = 'util encoding service';

    const TWIG_ENVIRONMENT = 'twig environment';

    const PLUGINS_CMS_PAGE_TABLE_EXPANDER = 'PLUGINS_CMS_PAGE_TABLE_EXPANDER';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideCommunicationLayerDependencies(Container $container)
    {
        $container[static::FACADE_LOCALE] = function (Container $container) {
            return new CmsGuiToLocaleBridge($container->getLocator()->locale()->facade());
        };

        $container[static::FACADE_CMS] = function (Container $container) {
            return new CmsGuiToCmsBridge($container->getLocator()->cms()->facade());
        };

        $container[static::FACADE_GLOSSARY] = function (Container $container) {
            return new CmsGuiToCmsGlossaryFacadeBridge($container->getLocator()->glossary()->facade());
        };

        $container[static::QUERY_CONTAINER_CMS] = function (Container $container) {
            return new CmsGuiToCmsQueryContainerBridge($container->getLocator()->cms()->queryContainer());
        };

        $container[static::FACADE_URL] = function (Container $container) {
            return new CmsGuiToUrlBridge($container->getLocator()->url()->facade());
        };

        $container[static::SERVICE_UTIL_ENCODING] = function (Container $container) {
            return new CmsGuiToUtilEncodingBridge($container->getLocator()->utilEncoding()->service());
        };

        $container[static::TWIG_ENVIRONMENT] = function (Container $container) {
            return $this->getTwigEnvironment();
        };

        $container = $this->addCmsPageTableExpanderPlugins($container);

        return $container;
    }

    /**
     * @return \Twig_Environment
     */
    protected function getTwigEnvironment()
    {
        $pimplePlugin = new Pimple();
        return $pimplePlugin->getApplication()['twig'];
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addCmsPageTableExpanderPlugins(Container $container)
    {
        $container[static::PLUGINS_CMS_PAGE_TABLE_EXPANDER] = function (Container $container) {
            return $this->getCmsPageTableExpanderPlugins();
        };

        return $container;
    }

    /**
     * @return \Spryker\Zed\CmsGui\Dependency\Plugin\CmsPageTableExpanderPluginInterface[]
     */
    protected function getCmsPageTableExpanderPlugins()
    {
        return [];
    }

}
