<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Application\Business\Model\Twig;

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
            new \SprykerFeature\Zed\Gui\Communication\Plugin\Twig\Button(),
            new \SprykerFeature\Zed\Gui\Communication\Plugin\Twig\ConfirmDialog(),
            new \SprykerFeature\Zed\Gui\Communication\Plugin\Twig\FormatPrice(),
            new \SprykerFeature\Zed\Gui\Communication\Plugin\Twig\Grid(),
            new \SprykerFeature\Zed\Gui\Communication\Plugin\Twig\GridConfirmDialog(),
            new \SprykerFeature\Zed\Gui\Communication\Plugin\Twig\ListGroup(),
            new \SprykerFeature\Zed\Gui\Communication\Plugin\Twig\Modal(),
            new \SprykerFeature\Zed\Gui\Communication\Plugin\Twig\Panel(),
            new \SprykerFeature\Zed\Gui\Communication\Plugin\Twig\Widget(),
            new \SprykerFeature\Zed\Gui\Communication\Plugin\Twig\StaticPath(),
            new \SprykerFeature\Zed\Gui\Communication\Plugin\Twig\Inspinia\BackActionButton(),
            new \SprykerFeature\Zed\Gui\Communication\Plugin\Twig\Inspinia\CreateActionButton(),
            new \SprykerFeature\Zed\Gui\Communication\Plugin\Twig\Inspinia\ViewActionButton(),
            new \SprykerFeature\Zed\Gui\Communication\Plugin\Twig\Inspinia\EditActionButton(),
            new \SprykerFeature\Zed\Gui\Communication\Plugin\Twig\UrlFunction(),
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
