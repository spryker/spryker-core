<?php

namespace SprykerEngine\Client\Kernel\Service;

use SprykerEngine\Client\Kernel\BundleDependencyProviderLocator;
use SprykerEngine\Client\Kernel\Container;
use SprykerEngine\Shared\Kernel\AbstractLocator;
use SprykerEngine\Shared\Kernel\ClassResolver\ClassNotFoundException;
use SprykerEngine\Shared\Kernel\Locator\LocatorException;
use SprykerEngine\Shared\Kernel\LocatorLocatorInterface;
use SprykerFeature\Shared\Library\Log;

class ClientLocator extends AbstractLocator
{

    const LOCATABLE_SUFFIX = 'Client';

    /**
     * @var string
     */
    protected $bundle = 'Kernel';

    /**
     * @var string
     */
    protected $layer = 'Service';

    /**
     * @var string
     */
    protected $suffix = 'Factory';

    /**
     * @var string
     */
    protected $application = 'Client';

    /**
     * @param string $bundle
     * @param LocatorLocatorInterface $locator
     * @param null $className
     *
     * @throws LocatorException
     *
     * @return object
     */
    public function locate($bundle, LocatorLocatorInterface $locator, $className = null)
    {
        $factory = $this->getFactory($bundle);

        $locatedClient = $factory->create($bundle . self::LOCATABLE_SUFFIX, $factory, $locator);

        try {
            $bundleDependencyProviderLocator = new BundleDependencyProviderLocator(); // TODO Make singleton because of performance
            $bundleBuilder = $bundleDependencyProviderLocator->locate($bundle, $locator);

            $container = new Container();
            $bundleBuilder->provideServiceLayerDependencies($container);
            $locatedClient->setExternalDependencies($container);
        } catch (ClassNotFoundException $e) {
            // TODO remove try-catch when all bundles have a Builder
            Log::log('Yves - ' . $bundle, 'builder_missing.log');
        }

        return $locatedClient;
    }

}
