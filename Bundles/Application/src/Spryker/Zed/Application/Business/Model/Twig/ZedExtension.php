<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Application\Business\Model\Twig;

use Spryker\Zed\Gui\Communication\Plugin\Twig\UrlFunction;
use Spryker\Zed\Gui\Communication\Plugin\Twig\Inspinia\EditActionButton;
use Spryker\Zed\Gui\Communication\Plugin\Twig\Inspinia\ViewActionButton;
use Spryker\Zed\Gui\Communication\Plugin\Twig\Inspinia\CreateActionButton;
use Spryker\Zed\Gui\Communication\Plugin\Twig\Inspinia\BackActionButton;
use Spryker\Zed\Gui\Communication\Plugin\Twig\StaticPath;
use Spryker\Zed\Gui\Communication\Plugin\Twig\Panel;
use Spryker\Zed\Gui\Communication\Plugin\Twig\Modal;
use Spryker\Zed\Gui\Communication\Plugin\Twig\ListGroup;
use Spryker\Zed\Gui\Communication\Plugin\Twig\GridConfirmDialog;
use Spryker\Zed\Gui\Communication\Plugin\Twig\Grid;
use Spryker\Zed\Gui\Communication\Plugin\Twig\FormatPrice;
use Spryker\Zed\Gui\Communication\Plugin\Twig\ConfirmDialog;
use Spryker\Zed\Gui\Communication\Plugin\Twig\Button;

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
            new Button(),
            new ConfirmDialog(),
            new FormatPrice(),
            new Grid(),
            new GridConfirmDialog(),
            new ListGroup(),
            new Modal(),
            new Panel(),
            new StaticPath(),
            new BackActionButton(),
            new CreateActionButton(),
            new ViewActionButton(),
            new EditActionButton(),
            new UrlFunction(),
        ];

        return $functions;
    }

}
