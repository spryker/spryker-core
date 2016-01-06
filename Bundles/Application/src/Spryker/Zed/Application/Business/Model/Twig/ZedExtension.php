<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Application\Business\Model\Twig;

use Spryker\Zed\Gui\Communication\Plugin\Twig\UrlFunction;
use Spryker\Zed\Gui\Communication\Plugin\Twig\Inspinia\EditActionButtonFunction;
use Spryker\Zed\Gui\Communication\Plugin\Twig\Inspinia\ViewActionButtonFunction;
use Spryker\Zed\Gui\Communication\Plugin\Twig\Inspinia\CreateActionButtonFunction;
use Spryker\Zed\Gui\Communication\Plugin\Twig\Inspinia\BackActionButtonFunction;
use Spryker\Zed\Gui\Communication\Plugin\Twig\StaticPath;
use Spryker\Zed\Gui\Communication\Plugin\Twig\Panel;
use Spryker\Zed\Gui\Communication\Plugin\Twig\Modal;
use Spryker\Zed\Gui\Communication\Plugin\Twig\ListGroup;
use Spryker\Zed\Gui\Communication\Plugin\Twig\FormatPriceFunction;

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
            new ListGroup(),
            new Modal(),
            new Panel(),
            new StaticPath(),
            new BackActionButtonFunction(),
            new CreateActionButtonFunction(),
            new ViewActionButtonFunction(),
            new EditActionButtonFunction(),
            new UrlFunction(),
        ];

        return $functions;
    }

}
