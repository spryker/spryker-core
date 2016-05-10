<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Gui;

use Spryker\Zed\Gui\Communication\Plugin\Twig\ActionButtons\BackActionButtonFunction;
use Spryker\Zed\Gui\Communication\Plugin\Twig\ActionButtons\CreateActionButtonFunction;
use Spryker\Zed\Gui\Communication\Plugin\Twig\ActionButtons\EditActionButtonFunction;
use Spryker\Zed\Gui\Communication\Plugin\Twig\ActionButtons\ViewActionButtonFunction;
use Spryker\Zed\Gui\Communication\Plugin\Twig\AssetsPathFunction;
use Spryker\Zed\Gui\Communication\Plugin\Twig\FormatPriceFunction;
use Spryker\Zed\Gui\Communication\Plugin\Twig\UrlFunction;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;

class GuiDependencyProvider extends AbstractBundleDependencyProvider
{
    public function provideCommunicationLayerDependencies(Container $container)
    {
        $container['GUI_TWIG_EXTENSIONS'] = function (Container $container) {
            return [
                new FormatPriceFunction(),
                new AssetsPathFunction(),
                new BackActionButtonFunction(),
                new CreateActionButtonFunction(),
                new ViewActionButtonFunction(),
                new EditActionButtonFunction(),
                new UrlFunction(),
            ];
        };
    }

}
