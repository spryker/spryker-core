<?php

namespace SprykerFeature\Zed\Price\Business;

use Generated\Zed\Ide\AutoCompletion;
use SprykerEngine\Shared\Kernel\LocatorLocatorInterface;


class PriceSettings
{
    const DEFAULT_PRICE_TYPE = 'DEFAULT';

    /**
     * @var AutoCompletion
     */
    protected $locator;

    /**
     * @param LocatorLocatorInterface $locator
     */
    public function __construct(LocatorLocatorInterface $locator)
    {
        $this->locator = $locator;
    }

    /**
     * @return string
     */
    public function getPriceTypeDefaultName()
    {
        return self::DEFAULT_PRICE_TYPE;
    }
}
