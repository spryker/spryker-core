<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\Cms;

use Spryker\Shared\Config\Environment;
use Spryker\Yves\Kernel\AbstractBundleDependencyProvider;
use Spryker\Yves\Kernel\Container;
use Spryker\Yves\Kernel\Plugin\Pimple;

class CmsDependencyProvider extends AbstractBundleDependencyProvider
{

    const CMS_CONTENT_WIDGET_PLUGINS = 'cms_content_widget_plugins';

    const TWIG_ENVIRONMENT = 'twig_environment';
    const APPLICATION_ENVIRONMENT = 'application_environment';

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    public function provideDependencies(Container $container)
    {
        $container[static::CMS_CONTENT_WIDGET_PLUGINS] = function (Container $container) {
            return $this->getCmsContentWidgetPlugins();
        };

        $container[static::TWIG_ENVIRONMENT] = function (Container $container) {
            return $this->getTwigEnvironment();
        };

        $container[static::APPLICATION_ENVIRONMENT] = function (Container $container) {
            return $this->getApplicationEnvironment();
        };

        return $container;
    }

    /**
     * @return \Twig_Environment
     */
    protected function getTwigEnvironment()
    {
        $pimplePlugin = new Pimple();
        $twig = $pimplePlugin->getApplication()['twig'];

        return $twig;
    }

    /**
     * @return array|\Spryker\Yves\Cms\Dependency\CmsContentWidgetPluginInterface[]
     */
    public function getCmsContentWidgetPlugins()
    {
        return [];
    }

    /**
     * @return \Spryker\Shared\Config\Environment
     */
    protected function getApplicationEnvironment()
    {
        return Environment::getInstance();
    }

}
