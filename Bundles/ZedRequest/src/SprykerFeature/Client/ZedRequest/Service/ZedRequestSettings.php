<?php

namespace SprykerFeature\Client\ZedRequest\Service;

use Generated\Client\Ide\AutoCompletion;
use SprykerEngine\Shared\Kernel\LocatorLocatorInterface;

class ZedRequestSettings
{
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
     * @return array
     */
    public function getHeaders()
    {
        return [];
    }
}
