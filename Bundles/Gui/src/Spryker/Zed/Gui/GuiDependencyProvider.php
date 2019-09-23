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
use Spryker\Zed\Gui\Communication\Plugin\Twig\Buttons\ButtonGroupFunction;
use Spryker\Zed\Gui\Communication\Plugin\Twig\Buttons\Form\SubmitButtonFunction;
use Spryker\Zed\Gui\Communication\Plugin\Twig\Buttons\Table\BackTableButtonFunction;
use Spryker\Zed\Gui\Communication\Plugin\Twig\Buttons\Table\CreateTableButtonFunction;
use Spryker\Zed\Gui\Communication\Plugin\Twig\Buttons\Table\EditTableButtonFunction;
use Spryker\Zed\Gui\Communication\Plugin\Twig\Buttons\Table\RemoveTableButtonFunction;
use Spryker\Zed\Gui\Communication\Plugin\Twig\Buttons\Table\ViewTableButtonFunction;
use Spryker\Zed\Gui\Communication\Plugin\Twig\TabsFunction;
use Spryker\Zed\Gui\Communication\Plugin\Twig\UrlDecodeFunction;
use Spryker\Zed\Gui\Communication\Plugin\Twig\UrlFunction;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;

/**
 * @method \Spryker\Zed\Gui\GuiConfig getConfig()
 */
class GuiDependencyProvider extends AbstractBundleDependencyProvider
{
    public const GUI_TWIG_FUNCTIONS = 'gui_twig_functions';
    public const GUI_TWIG_FILTERS = 'gui_twig_filters';

    public const TWIG_GUI_FUNCTIONS = 'TWIG_GUI_FUNCTIONS';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideCommunicationLayerDependencies(Container $container)
    {
        $container = $this->addTwigFunctions($container);
        $container = $this->addTwigFilter($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addTwigFunctions(Container $container)
    {
        $container[static::GUI_TWIG_FUNCTIONS] = function () {
            return $this->getTwigFunctions();
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addTwigFilter(Container $container)
    {
        $container[static::GUI_TWIG_FILTERS] = function () {
            return $this->getTwigFilters();
        };

        return $container;
    }

    /**
     * @deprecated Will be removed without replacement.
     *
     * @return \Twig\TwigFunction[]
     */
    protected function getTwigFunctions()
    {
        return [
            new AssetsPathFunction(),
            new TabsFunction(),
            new UrlFunction(),
            new UrlDecodeFunction(),
            // navigation buttons
            new ButtonGroupFunction(),
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
            // Form buttons
            new SubmitButtonFunction(),
        ];
    }

    /**
     * @return \Twig\TwigFilter[]
     */
    protected function getTwigFilters()
    {
        return [];
    }
}
