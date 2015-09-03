<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerEngine\Shared\Kernel;

use SprykerEngine\Shared\Kernel\Locator\LocatorInterface;

class TransferLocator implements LocatorInterface
{

    /**
     * @param string $bundle
     * @param LocatorLocatorInterface $locator
     * @param string $className
     *
     * @return object
     */
    public function locate($bundle, LocatorLocatorInterface $locator, $className = null)
    {
        return ClassMapFactory::getInstance()->create(APPLICATION, $bundle, $className);
    }

    /**
     * @param string $bundle
     *
     * @throws \ErrorException
     *
     * @return bool
     */
    public function canLocate($bundle)
    {
        throw new \ErrorException('Not available here');
    }

}
