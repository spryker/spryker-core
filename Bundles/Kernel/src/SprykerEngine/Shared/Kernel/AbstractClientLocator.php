<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerEngine\Shared\Kernel;

use SprykerEngine\Shared\Kernel\Locator\LocatorException;

abstract class AbstractClientLocator extends AbstractLocator
{

    const PREFIX = 'Provider';
    const SUFFIX = 'ClientProvider';

    /**
     * @var array
     */
    protected $cachedClients = [];

    /**
     * @param string $bundle
     * @param LocatorLocatorInterface $locator
     * @param null|string $className
     *
     * @throws LocatorException
     *
     * @return object
     */
    public function locate($bundle, LocatorLocatorInterface $locator, $className = null)
    {
        $factory = $this->getFactory($bundle);

        $key = self::PREFIX . ucfirst($className) . self::SUFFIX;
        if (!array_key_exists($key, $this->cachedClients)) {
            $this->cachedClients[$key] = $factory->create($key, $factory, $locator);
        }

        return $this->cachedClients[$key];
    }

}
