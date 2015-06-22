<?php

namespace SprykerFeature\Client\KvStorage;

use SprykerEngine\Shared\Kernel\AbstractLocator;
use SprykerEngine\Shared\Kernel\Locator\LocatorException;
use SprykerEngine\Shared\Kernel\Locator\LocatorInterface;
use SprykerEngine\Shared\Kernel\LocatorLocatorInterface;

class StorageLocator extends AbstractLocator
{

    const LOCATABLE_SUFFIX = 'Storage';

    /**
     * @var string
     */
    protected $factoryClassNamePattern = '\\{{namespace}}\\Client\\KvStorage\\Factory';

    /**
     * @param string $bundle
     * @param LocatorLocatorInterface $locator
     * @param null $className
     *
     * @throws LocatorException
     * @return object
     */
    public function locate($bundle, LocatorLocatorInterface $locator, $className = null)
    {
        $factory = $this->getFactory($bundle);

        return $factory->create($bundle . self::LOCATABLE_SUFFIX, $factory, $locator);
    }
}
