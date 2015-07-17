<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerEngine\Zed\Kernel;

use SprykerEngine\Zed\Kernel\Business\AbstractFacade;
use SprykerEngine\Zed\Kernel\Business\Factory as BusinessFactory;
use SprykerEngine\Zed\Kernel\Communication\AbstractPlugin;
use SprykerEngine\Zed\Kernel\Communication\Factory as CommunicationFactory;
use SprykerEngine\Zed\Kernel\Persistence\AbstractQueryContainer;
use SprykerEngine\Zed\Kernel\Persistence\Factory as PersistenceFactory;

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
        if (is_null($namespace)) {
            $namespace = $this->getNamespaceFromTestClassName();
        }

        if (is_null($bundle)) {
            $bundle = $this->getBundleFromTestClassName();
        }

        $factory = new BusinessFactory($bundle);

        $facadeClassName = '\\' . $namespace . '\\Zed\\' . $bundle . '\\Business\\' . $bundle . 'Facade';
        /** @var AbstractFacade $facade */
        $facade = new $facadeClassName($factory, Locator::getInstance());

        $queryContainer = $this->getQueryContainer($namespace, $bundle);
        if ($queryContainer) {
            $queryContainer->setExternalDependencies($this->getContainer($namespace, $bundle));
            $facade->setOwnQueryContainer($queryContainer);
        }

        $facade->setExternalDependencies($this->getContainer($namespace, $bundle));

        return $facade;
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
        $factory = new PersistenceFactory($bundle);
        /** @var AbstractQueryContainer $queryContainer */
        $queryContainer = new $queryContainerClassName($factory, Locator::getInstance());
        $queryContainer->setContainer($this->getContainer($namespace, $bundle));

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

        $factory = new CommunicationFactory($bundle);

        /** @var AbstractPlugin $plugin */
        $plugin = new $pluginClassName($factory, Locator::getInstance());
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
     * @return string
     */
    private function getBundleFromTestClassName()
    {
        return $this->getClassNamePart(get_class($this), 3);
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
