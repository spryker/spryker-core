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
            $locator->gui()->pluginTwigButton(),
            $locator->gui()->pluginTwigConfirmDialog(),
            $locator->gui()->pluginTwigFormatPrice(),
            $locator->gui()->pluginTwigGrid(),
            $locator->gui()->pluginTwigGridConfirmDialog(),
            $locator->gui()->pluginTwigListGroup(),
            $locator->gui()->pluginTwigModal(),
            $locator->gui()->pluginTwigPanel(),
            $locator->gui()->pluginTwigWidget(),
            $locator->gui()->pluginTwigStaticPath(),
            $locator->gui()->pluginTwigInspiniaBackActionButton(),
            $locator->gui()->pluginTwigInspiniaCreateActionButton(),
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
