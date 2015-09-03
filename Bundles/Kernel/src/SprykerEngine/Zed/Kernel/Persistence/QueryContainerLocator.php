<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerEngine\Zed\Kernel\Persistence;

use Generated\Zed\Ide\AutoCompletion;
use SprykerEngine\Shared\Kernel\ClassResolver\ClassNotFoundException;
use SprykerEngine\Shared\Kernel\Locator\LocatorException;
use SprykerEngine\Shared\Kernel\LocatorLocatorInterface;
use SprykerEngine\Shared\Kernel\AbstractLocator;
use SprykerEngine\Zed\Kernel\BundleDependencyProviderLocator;
use SprykerEngine\Zed\Kernel\Container;

class QueryContainerLocator extends AbstractLocator
{

    const PROPEL_CONNECTION = 'propel connection';

    /**
     * @var string
     */
    protected $bundle = 'Kernel';

    /**
     * @var string
     */
    protected $layer = 'Persistence';

    /**
     * @var string
     */
    protected $suffix = 'Factory';

    /**
     * @var string
     */
    protected $application = 'Zed';

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

        $queryContainer = $factory->create($bundle . 'QueryContainer', $factory, $locator);

        try {
            // TODO Make singleton because of performance
            $bundleConfigLocator = new BundleDependencyProviderLocator();
            $bundleBuilder = $bundleConfigLocator->locate($bundle, $locator);
            $container = new Container();
            $container[self::PROPEL_CONNECTION] = function () use ($locator) {
                /** @var $locator AutoCompletion */
                return $locator->propel()->pluginConnection()->get();
            };
            $bundleBuilder->providePersistenceLayerDependencies($container);
            $queryContainer->setContainer($container);
            $queryContainer->setExternalDependencies($container);
        } catch (ClassNotFoundException $e) {
            // TODO remove try-catch when all bundles have a DependencyProvider
            \SprykerFeature_Shared_Library_Log::log($bundle, 'builder_missing.log');
        }

        return $queryContainer;
    }

    /**
     * @param string $bundle
     *
     * @return bool
     */
    public function canLocate($bundle)
    {
        $factory = $this->getFactory($bundle);

        return $factory->exists($bundle . 'QueryContainer');
    }

}
