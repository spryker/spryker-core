<?php

namespace SprykerEngine\Zed\Kernel\Communication;

use SprykerEngine\Shared\Kernel\AbstractLocator;
use SprykerEngine\Shared\Kernel\ClassResolver;
use SprykerEngine\Shared\Kernel\Locator\LocatorException;
use SprykerEngine\Shared\Kernel\LocatorLocatorInterface;

class PluginLocator extends AbstractLocator
{

    /**
     * @var string
     */
    protected $factoryClassNamePattern = '\\{{namespace}}\\Zed\\Kernel\\Communication\\Factory';

    /**
     * @param string $bundle
     * @param LocatorLocatorInterface $locator
     * @param null|string $className
     *
     * @return object
     * @throws LocatorException
     */
    public function locate($bundle, LocatorLocatorInterface $locator, $className = null)
    {
        $factory = $this->getFactory($bundle);

        $plugin = $factory->create('Plugin' . $className, $factory, $locator);

        // TODO REFACTOR -  move to constructor when all controllers are upgraded
        $bundleName = lcfirst($bundle);

        if ($locator->$bundleName()->hasFacade()) {

            // TODO temporary hack needed because the "UI-plugins" do not extend AbstractPlugin....
            if (method_exists($plugin, 'setOwnFacade')) {
                $plugin->setOwnFacade($locator->$bundleName()->facade());
            }
        }

        return $plugin;
    }

}
