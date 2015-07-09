<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Yves\Twig;

use Generated\Yves\Ide\AutoCompletion;
use SprykerEngine\Shared\Kernel\LocatorLocatorInterface;
use SprykerFeature\Yves\Twig\Dependency\Plugin\TwigFilterPluginInterface;
use SprykerFeature\Yves\Twig\Dependency\Plugin\TwigFunctionPluginInterface;

class TwigSettings
{

    /**
     * @var AutoCompletion
     */
    private $locator;

    /**
     * @param LocatorLocatorInterface $locator
     */
    public function __construct(LocatorLocatorInterface $locator)
    {
        $this->locator = $locator;
    }

    /**
     * @return TwigFilterPluginInterface[]
     */
    public function getTwigFilters()
    {
        return [
            $this->getLocator()->twig()->pluginTwigNative(),
        ];
    }

    /**
     * @return TwigFunctionPluginInterface[]
     */
    public function getTwigFunctions()
    {
        return [
            $this->getLocator()->price()->pluginTwigPrice(),
            $this->getLocator()->cms()->pluginTwigCms(),
        ];
    }

    /**
     * @return AutoCompletion
     */
    protected function getLocator()
    {
        return $this->locator;
    }

}
