<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Gui;

use Spryker\Zed\Gui\Dependency\Service\GuiToUtilSanitizeXssServiceBridge;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;

/**
 * @method \Spryker\Zed\Gui\GuiConfig getConfig()
 */
class GuiDependencyProvider extends AbstractBundleDependencyProvider
{
    public const SERVICE_UTIL_SANITIZE_XSS = 'SERVICE_UTIL_SANITIZE_XSS';

    public const GUI_TWIG_FUNCTIONS = 'gui_twig_functions';
    public const GUI_TWIG_FILTERS = 'gui_twig_filters';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideCommunicationLayerDependencies(Container $container)
    {
        $container = parent::provideCommunicationLayerDependencies($container);
        $container = $this->addTwigFilter($container);
        $container = $this->addUtilSanitizeXssService($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addTwigFilter(Container $container)
    {
        $container->set(static::GUI_TWIG_FILTERS, function () {
            return $this->getTwigFilters();
        });

        return $container;
    }

    /**
     * @return \Twig\TwigFilter[]
     */
    protected function getTwigFilters()
    {
        return [];
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addUtilSanitizeXssService(Container $container): Container
    {
        $container->set(static::SERVICE_UTIL_SANITIZE_XSS, function (Container $container) {
            return new GuiToUtilSanitizeXssServiceBridge(
                $container->getLocator()->utilSanitizeXss()->service()
            );
        });

        return $container;
    }
}
