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
        $locator = $this->getLocator();
        $functions = [
            $locator->ui()->pluginTwigButton(),
            $locator->ui()->pluginTwigConfirmDialog(),
            $locator->ui()->pluginTwigFormatPrice(),
            $locator->ui()->pluginTwigGrid(),
            $locator->ui()->pluginTwigGridConfirmDialog(),
            $locator->ui()->pluginTwigListGroup(),
            $locator->ui()->pluginTwigModal(),
            $locator->ui()->pluginTwigPanel(),
            $locator->ui()->pluginTwigWidget(),
            $locator->ui()->pluginTwigStaticPath(),
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
