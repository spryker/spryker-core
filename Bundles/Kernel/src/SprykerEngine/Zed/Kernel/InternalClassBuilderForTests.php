<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerEngine\Zed\Kernel;

use Generated\Zed\Ide\AutoCompletion;
use SprykerEngine\Shared\Kernel\LocatorLocatorInterface;
use SprykerEngine\Zed\Kernel\Business\AbstractFacade;
use SprykerEngine\Zed\Kernel\Communication\AbstractPlugin;
use SprykerEngine\Zed\Kernel\Persistence\AbstractQueryContainer;

trait InternalClassBuilderForTests
{

    /**
     * @param null $namespace
     * @param null $bundle
     *
     * @return mixed
     */
    public function getFacade($namespace = null, $bundle = null)
    {
        if ($namespace === null) {
            $namespace = $this->getNamespaceFromTestClassName();
        }

        if ($bundle === null) {
            $bundle = $this->getBundleFromTestClassName();
        }

        $facade = $this->getFacadeByClassName($namespace, $bundle);

        $queryContainer = $this->getQueryContainer($namespace, $bundle);
        if ($queryContainer) {
            $queryContainer->setExternalDependencies($this->getContainer($namespace, $bundle));
            $facade->setOwnQueryContainer($queryContainer);
        }

        $facade->setExternalDependencies($this->getContainer($namespace, $bundle));

        return $facade;
    }

    /**
     * @param AbstractBundleConfig $bundleConfig
     *
     * @return AbstractDependencyContainer
     */
    protected function getDependencyContainer(AbstractBundleConfig $bundleConfig = null)
    {
        $namespace = $this->getNamespaceFromTestClassName();
        $bundle = $this->getBundleFromTestClassName();
        $layer = $this->getLayerFromTestClassName();

        $dependencyContainer = $this->getDependencyContainerByClassName($namespace, $bundle, $layer);

        if ($bundleConfig !== null) {
            $dependencyContainer->setConfig($bundleConfig);
        }

        return $dependencyContainer;
    }

    /**
     * @return AutoCompletion|LocatorLocatorInterface
     */
    private function getLocator()
    {
        return Locator::getInstance();
    }

    /**
     * @param $namespace
     * @param $bundle
     *
     * @return Container
     */
    private function getContainer($namespace, $bundle)
    {
        $container = new Container();

        $dependencyProviderClassName = $this->getDependencyProviderClassName($namespace, $bundle);

        /** @var AbstractBundleDependencyProvider $dependencyProvider */
        $dependencyProvider = new $dependencyProviderClassName();

        $dependencyProvider->provideBusinessLayerDependencies($container);
        $dependencyProvider->provideCommunicationLayerDependencies($container);
        $dependencyProvider->providePersistenceLayerDependencies($container);

        return $container;
    }

    /**
     * @param string $namespace
     * @param string $bundle
     *
     * @return bool|string
     */
    private function getDependencyProviderClassName($namespace, $bundle)
    {
        $dependencyProviderClassName = '\\' . $namespace . '\\Zed\\' . $bundle . '\\' . $bundle . 'DependencyProvider';
        if (class_exists($dependencyProviderClassName)) {
            return $dependencyProviderClassName;
        }
        $dependencyProviderClassName = '\\SprykerFeature\\Zed\\' . $bundle . '\\' . $bundle . 'DependencyProvider';
        if (class_exists($dependencyProviderClassName)) {
            return $dependencyProviderClassName;
        }
        $dependencyProviderClassName = '\\SprykerEngine\\Zed\\' . $bundle . '\\' . $bundle . 'DependencyProvider';
        if (class_exists($dependencyProviderClassName)) {
            return $dependencyProviderClassName;
        }

        return false;
    }

    /**
     * @param $namespace
     * @param $bundle
     *
     * @return bool|AbstractQueryContainer
     */
    private function getQueryContainer($namespace, $bundle)
    {
        $queryContainerClassName = '\\' . $namespace . '\\Zed\\' . $bundle . '\\Persistence\\' . $bundle . 'QueryContainer';
        if (!class_exists($queryContainerClassName)) {
            return false;
        }
        /** @var AbstractQueryContainer $queryContainer */
        $queryContainer = new $queryContainerClassName();
        $queryContainer->setExternalDependencies($this->getContainer($namespace, $bundle));

        return $queryContainer;
    }

    /**
     * @param $pluginClassName
     *
     * @return AbstractPlugin
     */
    public function getPluginByClassName($pluginClassName)
    {
        $namespace = $this->getNamespaceFromPassedClassName($pluginClassName);
        $bundle = $this->getBundleFromPassedClassName($pluginClassName);

        /** @var AbstractPlugin $plugin */
        $plugin = new $pluginClassName();
        $plugin->setExternalDependencies($this->getContainer($namespace, $bundle));
        $plugin->setOwnFacade($this->getFacade($namespace, $bundle));

        return $plugin;
    }

    /**
     * @return string
     */
    private function getNamespaceFromTestClassName()
    {
        return $this->getClassNamePart(get_class($this), 1);
    }

    /**
     * @param string $namespace
     * @param string $bundle
     *
     * @return AbstractFacade
     */
    protected function getFacadeByClassName($namespace, $bundle)
    {
        $facadeClassName = '\\' . $namespace . '\\Zed\\' . $bundle . '\\Business\\' . $bundle . 'Facade';
        $facade = new $facadeClassName();

        return $facade;
    }

    /**
     * @param string $namespace
     * @param string $bundle
     * @param string $layer
     *
     * @return AbstractDependencyContainer
     */
    protected function getDependencyContainerByClassName($namespace, $bundle, $layer)
    {
        $className = '\\' . $namespace . '\\Zed\\' . $bundle . '\\' . $layer . '\\' . $bundle . 'DependencyContainer';
        $class = new $className();

        return $class;
    }

    /**
     * @return string
     */
    private function getApplicationFromTestClassName()
    {
        return $this->getClassNamePart(get_class($this), 2);
    }

    /**
     * @return string
     */
    private function getBundleFromTestClassName()
    {
        return $this->getClassNamePart(get_class($this), 3);
    }

    /**
     * @return string
     */
    private function getLayerFromTestClassName()
    {
        return $this->getClassNamePart(get_class($this), 4);
    }

    /**
     * @param $className
     *
     * @return string
     */
    private function getNamespaceFromPassedClassName($className)
    {
        return $this->getClassNamePart($className, 0);
    }

    /**
     * @param $className
     *
     * @return string
     */
    private function getBundleFromPassedClassName($className)
    {
        return $this->getClassNamePart($className, 2);
    }

    /**
     * @param $className
     * @param $position
     *
     * @return string
     */
    private function getClassNamePart($className, $position)
    {
        $classNameParts = explode('\\', $className);
        $part = $classNameParts[$position];

        return $part;
    }

}
