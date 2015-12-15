<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Shared\Kernel;

use Spryker\Shared\Kernel\Locator\LocatorException;

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
     * @param string|null $className
     *
     * @throws LocatorException
     *
     * @return object
     */
    public function locate($bundle, LocatorLocatorInterface $locator, $className = null)
    {
        $factory = $this->getFactory($bundle);

        $key = self::PREFIX . ucfirst($className) . self::SUFFIX;
        if (!isset($this->cachedClients[$key])) {
            $this->cachedClients[$key] = $factory->create($key, $factory, $locator);
        }

        return $this->cachedClients[$key];
    }

}
