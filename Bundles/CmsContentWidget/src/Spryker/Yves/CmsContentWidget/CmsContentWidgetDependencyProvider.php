<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\CmsContentWidget;

use Spryker\Yves\Kernel\AbstractBundleDependencyProvider;
use Spryker\Yves\Kernel\Container;
use Spryker\Yves\Kernel\Plugin\Pimple;

class CmsContentWidgetDependencyProvider extends AbstractBundleDependencyProvider
{
    public const CMS_CONTENT_WIDGET_PLUGINS = 'CMS CONTENT WIDGET PLUGINS';

    public const TWIG_ENVIRONMENT = 'TWIG ENVIRONMENT';

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
     *
     * Returns list of cms content widget plugins which are twig functions used in cms content pages/blocks.
     * Should return key value pair where key is function name and value is concrete content widget plugin.
     *
     * @return \Spryker\Yves\CmsContentWidget\Dependency\CmsContentWidgetPluginInterface[]
     */
    public function getCmsContentWidgetPlugins()
    {
        return [];
    }
}
