<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerEngine\Zed\Kernel\Persistence\Propel;

use SprykerEngine\Shared\Kernel\Locator\LocatorInterface;
use SprykerEngine\Shared\Kernel\LocatorLocatorInterface;
use SprykerEngine\Shared\Kernel\ClassMapFactory;

class EntityLocator implements LocatorInterface
{

    /**
     * @param string $bundle
     * @param LocatorLocatorInterface $locator
     * @param null $className
     *
     * @throws \SprykerEngine\Shared\Kernel\ClassResolver\ClassNotFoundException
     *
     * @return object
     */
    public function locate($bundle, LocatorLocatorInterface $locator, $className = null)
    {
        $entity = ClassMapFactory::getInstance()->create('Zed', $bundle, 'Propel' . $className, 'Persistence');

        return $entity;
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
