<?php

namespace SprykerFeature\Zed\Calculation\Business\Model\Calculator;

use SprykerEngine\Shared\Kernel\LocatorLocatorInterface;
use SprykerEngine\Zed\Kernel\Locator;

abstract class AbstractCalculator
{
    /**
     * @var Locator|\Generated\Zed\Ide\AutoCompletion
     */
    protected $locator;

    /**
     * @param LocatorLocatorInterface $locator
     */
    public function __construct(LocatorLocatorInterface $locator)
    {
        $this->locator = $locator;
    }
}
