<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Application\Business\Model\Twig;

use SprykerFeature\Zed\Gui\Communication\Plugin\Twig\UrlFunction;
use SprykerFeature\Zed\Gui\Communication\Plugin\Twig\Inspinia\EditActionButton;
use SprykerFeature\Zed\Gui\Communication\Plugin\Twig\Inspinia\ViewActionButton;
use SprykerFeature\Zed\Gui\Communication\Plugin\Twig\Inspinia\CreateActionButton;
use SprykerFeature\Zed\Gui\Communication\Plugin\Twig\Inspinia\BackActionButton;
use SprykerFeature\Zed\Gui\Communication\Plugin\Twig\StaticPath;
use SprykerFeature\Zed\Gui\Communication\Plugin\Twig\Widget;
use SprykerFeature\Zed\Gui\Communication\Plugin\Twig\Panel;
use SprykerFeature\Zed\Gui\Communication\Plugin\Twig\Modal;
use SprykerFeature\Zed\Gui\Communication\Plugin\Twig\ListGroup;
use SprykerFeature\Zed\Gui\Communication\Plugin\Twig\GridConfirmDialog;
use SprykerFeature\Zed\Gui\Communication\Plugin\Twig\Grid;
use SprykerFeature\Zed\Gui\Communication\Plugin\Twig\FormatPrice;
use SprykerFeature\Zed\Gui\Communication\Plugin\Twig\ConfirmDialog;
use SprykerFeature\Zed\Gui\Communication\Plugin\Twig\Button;
use Generated\Zed\Ide\AutoCompletion;
use SprykerEngine\Zed\Kernel\Locator;

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
            new Widget(),
            new StaticPath(),
            new BackActionButton(),
            new CreateActionButton(),
            new ViewActionButton(),
            new EditActionButton(),
            new UrlFunction(),
        ];

        return $functions;
    }

    /**
     * @return AutoCompletion
     */
    private function getLocator()
    {
        return Locator::getInstance();
    }

}
