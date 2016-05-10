<?php

namespace Spryker\Zed\Gui\Communication\Plugin\Twig;

use Spryker\Zed\Gui\Communication\Plugin\Twig\Inspinia\BackActionButtonFunction;
use Spryker\Zed\Gui\Communication\Plugin\Twig\Inspinia\CreateActionButtonFunction;
use Spryker\Zed\Gui\Communication\Plugin\Twig\Inspinia\EditActionButtonFunction;
use Spryker\Zed\Gui\Communication\Plugin\Twig\Inspinia\ViewActionButtonFunction;

class GuiTwigExtensions extends \Twig_Extension
{
    /**
     * @return string
     */
    public function getName()
    {
        return 'zed_gui';
    }

    /**
     * @return array
     */
    public function getFunctions()
    {
        return [
            new FormatPriceFunction(),
            new ListGroupFunction(),
            new ModalFunction(),
            new PanelFunction(),
            new AssetsPathFunction(),
            new UrlFunction(),
            new BackActionButtonFunction(),
            new CreateActionButtonFunction(),
            new ViewActionButtonFunction(),
            new EditActionButtonFunction(),

//            new TableButtonFunction(),
        ];
    }
}