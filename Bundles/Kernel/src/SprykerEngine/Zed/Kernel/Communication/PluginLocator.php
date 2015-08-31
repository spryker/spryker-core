<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerEngine\Zed\Kernel\Communication;

use SprykerEngine\Shared\Kernel\AbstractLocator;
use SprykerEngine\Shared\Kernel\Locator\LocatorException;
use SprykerEngine\Shared\Kernel\LocatorLocatorInterface;
use SprykerEngine\Zed\Kernel\BundleDependencyProviderLocator;
use SprykerEngine\Zed\Kernel\Container;
use SprykerEngine\Shared\Kernel\Factory2;

class PluginLocator extends AbstractLocator
{

    /**
     * @var string
     */
    protected $factoryClassNamePattern = '\\{{namespace}}\\Zed\\Kernel\\Communication\\Factory';

    protected $bundle = 'Kernel';

    protected $layer = 'Communication';

    protected $suffix = 'Factory';

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

        $plugin = Factory2::getInstance()->create('Zed', $bundle, 'Plugin'.$className, 'Communication', [$factory, $locator]);

        // $plugin = $factory->create('Plugin' . $className, $factory, $locator);

        // @todo REFACTOR -  move to constructor when all controllers are upgraded
        $bundleName = lcfirst($bundle);

        $bundleConfigLocator = new BundleDependencyProviderLocator(); // @todo Make singleton because of performance
        $bundleBuilder = $bundleConfigLocator->locate($bundle, $locator);

        $container = new Container();
        $bundleBuilder->provideCommunicationLayerDependencies($container);
        if ($plugin instanceof AbstractPlugin) {
            $plugin->setExternalDependencies($container);
        }

        if ($locator->$bundleName()->hasFacade()) {
            // @todo temporary hack needed because the "UI-plugins" do not extend AbstractPlugin....
            if (method_exists($plugin, 'setOwnFacade')) {
                $plugin->setOwnFacade($locator->$bundleName()->facade());
            }
        }

        if ($locator->$bundleName()->hasQueryContainer()) {
            $plugin->setQueryContainer($locator->$bundleName()->queryContainer());
        }

        return $plugin;
    }

}
