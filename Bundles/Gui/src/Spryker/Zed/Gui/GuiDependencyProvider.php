<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Gui;

use Spryker\Zed\Gui\Communication\Plugin\Twig\AssetsPathFunction;
use Spryker\Zed\Gui\Communication\Plugin\Twig\Buttons\Action\BackActionButtonFunction;
use Spryker\Zed\Gui\Communication\Plugin\Twig\Buttons\Action\CreateActionButtonFunction;
use Spryker\Zed\Gui\Communication\Plugin\Twig\Buttons\Action\EditActionButtonFunction;
use Spryker\Zed\Gui\Communication\Plugin\Twig\Buttons\Action\RemoveActionButtonFunction;
use Spryker\Zed\Gui\Communication\Plugin\Twig\Buttons\Action\ViewActionButtonFunction;
use Spryker\Zed\Gui\Communication\Plugin\Twig\Buttons\Table\BackTableButtonFunction;
use Spryker\Zed\Gui\Communication\Plugin\Twig\Buttons\Table\CreateTableButtonFunction;
use Spryker\Zed\Gui\Communication\Plugin\Twig\Buttons\Table\EditTableButtonFunction;
use Spryker\Zed\Gui\Communication\Plugin\Twig\Buttons\Table\RemoveTableButtonFunction;
use Spryker\Zed\Gui\Communication\Plugin\Twig\Buttons\Table\ViewTableButtonFunction;
use Spryker\Zed\Gui\Communication\Plugin\Twig\TabsFunction;
use Spryker\Zed\Gui\Communication\Plugin\Twig\UrlFunction;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;

class GuiDependencyProvider extends AbstractBundleDependencyProvider
{

    const GUI_TWIG_FUNCTIONS = 'gui_twig_functions';
    const GUI_TWIG_FILTERS = 'gui_twig_filters';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideCommunicationLayerDependencies(Container $container)
    {
        $container[self::GUI_TWIG_FUNCTIONS] = function () {
            return $this->getTwigFunctions();
        };
        $container[self::GUI_TWIG_FILTERS] = function () {
            return $this->getTwigFilters();
        };

        return $container;
    }

    /**
     * @return \Twig_SimpleFunction[]
     */
    protected function getTwigFunctions()
    {
        return [
            new AssetsPathFunction(),
            new UrlFunction(),
            new TabsFunction(),
            // navigation buttons
            new BackActionButtonFunction(),
            new CreateActionButtonFunction(),
            new ViewActionButtonFunction(),
            new EditActionButtonFunction(),
            new RemoveActionButtonFunction(),
            // table row buttons
            new EditTableButtonFunction(),
            new BackTableButtonFunction(),
            new CreateTableButtonFunction(),
            new ViewTableButtonFunction(),
            new RemoveTableButtonFunction(),
        ];
    }

    /**
     * @return \Twig_SimpleFilter[]
     */
    protected function getTwigFilters()
    {
        return [];
    }

}
