<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Kernel\ClassResolver\DependencyInjector;

use Spryker\Shared\Config\Config;
use Spryker\Shared\Kernel\KernelConstants;
use Spryker\Zed\Kernel\ClassResolver\AbstractClassResolver;
use Spryker\Zed\Kernel\Dependency\Injector\DependencyInjectorCollection;

class DependencyInjectorResolver extends AbstractClassResolver
{
    const CLASS_NAME_PATTERN = '\\%1$s\\Zed\\%2$s%3$s\\Dependency\\Injector\\%4$sDependencyInjector';
    const KEY_FROM_BUNDLE = '%fromBundle%';

    /**
     * @var string
     */
    private $fromBundle;

    /**
     * @param object|string $callerClass
     *
     * @return \Spryker\Zed\Kernel\Dependency\Injector\DependencyInjectorCollectionInterface
     */
    public function resolve($callerClass)
    {
        $dependencyInjectorCollection = $this->getDependencyInjectorCollection();

        $this->setCallerClass($callerClass);
        $injectToBundle = $this->getClassInfo()->getBundle();
        $injectFromBundles = $this->getInjectorBundles($injectToBundle);

        foreach ($injectFromBundles as $injectFromBundle) {
            $this->fromBundle = $injectFromBundle;

            $this->unsetCurrentCacheEntry();

            if ($this->canResolve()) {
                $resolvedInjector = $this->getResolvedClassInstance();
                $dependencyInjectorCollection->addDependencyInjector($resolvedInjector);
            }
        }

        return $dependencyInjectorCollection;
    }

    /**
     * @return \Spryker\Zed\Kernel\Dependency\Injector\DependencyInjectorInterface
     */
    protected function getResolvedClassInstance()
    {
        /** @var \Spryker\Zed\Kernel\Dependency\Injector\DependencyInjectorInterface $class */
        $class = parent::getResolvedClassInstance();

        return $class;
    }

    /**
     * @return string
     */
    public function getClassPattern()
    {
        return sprintf(
            self::CLASS_NAME_PATTERN,
            self::KEY_NAMESPACE,
            self::KEY_FROM_BUNDLE,
            self::KEY_STORE,
            self::KEY_BUNDLE
        );
    }

    /**
     * @param string $namespace
     * @param string|null $store
     *
     * @return string
     */
    protected function buildClassName($namespace, $store = null)
    {
        $searchAndReplace = [
            self::KEY_NAMESPACE => $namespace,
            self::KEY_BUNDLE => $this->getClassInfo()->getBundle(),
            self::KEY_FROM_BUNDLE => $this->fromBundle,
            self::KEY_STORE => $store,
        ];

        $className = str_replace(
            array_keys($searchAndReplace),
            array_values($searchAndReplace),
            $this->getClassPattern()
        );

        return $className;
    }

    /**
     * @param string $injectToBundle
     *
     * @return array
     */
    protected function getInjectorBundles($injectToBundle)
    {
        $injectorConfiguration = $this->getDependencyInjectorConfiguration();
        if (!isset($injectorConfiguration[$injectToBundle])) {
            return [];
        }

        return $injectorConfiguration[$injectToBundle];
    }

    /**
     * @return array
     */
    protected function getDependencyInjectorConfiguration()
    {
        return Config::get(KernelConstants::DEPENDENCY_INJECTOR_ZED, []);
    }

    /**
     * @return \Spryker\Zed\Kernel\Dependency\Injector\DependencyInjectorCollection
     */
    protected function getDependencyInjectorCollection()
    {
        return new DependencyInjectorCollection();
    }
}
