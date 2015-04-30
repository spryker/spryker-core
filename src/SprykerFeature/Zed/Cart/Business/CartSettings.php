<?php

namespace SprykerFeature\Zed\Cart\Business;

use Generated\Zed\Ide\AutoCompletion;
use SprykerEngine\Shared\Kernel\LocatorLocatorInterface;
use SprykerFeature\Zed\Cart\Dependency\ItemExpanderPluginInterface;

class CartSettings
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
     * @return ItemExpanderPluginInterface[]
     */
    public function getCartItemPlugins()
    {
        return [];
    }

    /**
     * @return AutoCompletion|LocatorLocatorInterface
     */
    protected function getLocator()
    {
        return $this->locator;
    }
}