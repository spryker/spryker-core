<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Kernel\ClassResolver;

use Spryker\Shared\Config\Config;
use Spryker\Shared\Kernel\KernelConstants;
use Spryker\Shared\Kernel\Store;

abstract class AbstractClassResolver
{

    const KEY_NAMESPACE = '%namespace%';
    const KEY_BUNDLE = '%bundle%';
    const KEY_STORE = '%store%';

    /**
     * @var string
     */
    private $resolvedClassName;

    /**
     * @return string
     */
    abstract protected function getClassPattern();

    /**
     * @param string $namespace
     * @param string|null $store
     *
     * @return string
     */
    abstract protected function buildClassName($namespace, $store = null);

    /**
     * @return bool
     */
    public function canResolve()
    {
        $classNames = $this->buildClassNames();

        foreach ($classNames as $className) {
            if ($this->classExists($className)) {
                $this->resolvedClassName = $className;

                return true;
            }
        }

        return false;
    }

    /**
     * @param string $className
     *
     * @return bool
     */
    protected function classExists($className)
    {
        $resolverCacheManager = $this->createResolverCacheManager();

        if (!$resolverCacheManager->useCache()) {
            return class_exists($className);
        }

        $cacheProvider = $resolverCacheManager->createClassResolverCacheProvider();

        return $cacheProvider->getCache()->classExists($className);
    }

    /**
     * @return \Spryker\Shared\Kernel\ClassResolver\ResolverCacheFactoryInterface
     */
    protected function createResolverCacheManager()
    {
        return new ResolverCacheManager();
    }

    /**
     * @return object
     */
    protected function getResolvedClassInstance()
    {
        return new $this->resolvedClassName();
    }

    /**
     * @return array
     */
    private function buildClassNames()
    {
        $classNames = [];

        $classNames = $this->addProjectClassNames($classNames);
        $classNames = $this->addCoreClassNames($classNames);

        return $classNames;
    }

    /**
     * @param array $classNames
     *
     * @return array
     */
    private function addProjectClassNames(array $classNames)
    {
        $storeName = Store::getInstance()->getStoreName();
        foreach ($this->getProjectNamespaces() as $namespace) {
            $classNames[] = $this->buildClassName($namespace, $storeName);
            $classNames[] = $this->buildClassName($namespace);
        }

        return $classNames;
    }

    /**
     * @param array $classNames
     *
     * @return array
     */
    private function addCoreClassNames(array $classNames)
    {
        $storeName = Store::getInstance()->getStoreName();
        foreach ($this->getCoreNamespaces() as $namespace) {
            $classNames[] = $this->buildClassName($namespace, $storeName);
            $classNames[] = $this->buildClassName($namespace);
        }

        return $classNames;
    }

    /**
     * @return array
     */
    protected function getProjectNamespaces()
    {
        return Config::getInstance()->get(KernelConstants::PROJECT_NAMESPACES);
    }

    /**
     * @return array
     */
    protected function getCoreNamespaces()
    {
        return Config::getInstance()->get(KernelConstants::CORE_NAMESPACES);
    }

}
