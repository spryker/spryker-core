<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Application\Business\Model\Twig;

use Spryker\Zed\Gui\Communication\Plugin\Twig\AssetsPathFunction;
use Spryker\Zed\Gui\Communication\Plugin\Twig\Buttons\Action\BackActionButtonFunction;
use Spryker\Zed\Gui\Communication\Plugin\Twig\Buttons\Action\CreateActionButtonFunction;
use Spryker\Zed\Gui\Communication\Plugin\Twig\Buttons\Action\EditActionButtonFunction;
use Spryker\Zed\Gui\Communication\Plugin\Twig\Buttons\Action\ViewActionButtonFunction;
use Spryker\Zed\Gui\Communication\Plugin\Twig\FormatPriceFunction;
use Spryker\Zed\Gui\Communication\Plugin\Twig\ListGroupFunction;
use Spryker\Zed\Gui\Communication\Plugin\Twig\ModalFunction;
use Spryker\Zed\Gui\Communication\Plugin\Twig\PanelFunction;
use Spryker\Zed\Gui\Communication\Plugin\Twig\UrlFunction;

/**
 * @deprecated All bundles which want to add a function or something else to Twig does it now in a separate ServiceProvider of that bundle
 */
class ZedExtension extends \Twig_Extension
{

    /**
     * Returns the name of the extension.
     *
     * @return string The extension name
     */
    public function getName()
    {
        return 'zed';
    }

    /**
     * @return array
     */
    public function getFilters()
    {
        $filters = [];

        return $filters;
    }

    /**
     * @return array
     */
    public function getFunctions()
    {
        $functions = [
            new FormatPriceFunction(),
            new ListGroupFunction(),
            new ModalFunction(),
            new PanelFunction(),
            new AssetsPathFunction(),
            new BackActionButtonFunction(),
            new CreateActionButtonFunction(),
            new ViewActionButtonFunction(),
            new EditActionButtonFunction(),
            new UrlFunction(),
        ];

        return $functions;
    }

}
