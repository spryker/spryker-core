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
     * @var \Spryker\Shared\Kernel\ClassResolver\ClassInfo
     */
    protected $classInfo;

    /**
     * @var array
     */
    protected static $cache = [];

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
     * @param object|string $callerClass
     *
     * @return \Spryker\Shared\Kernel\ClassResolver\AbstractClassResolver
     */
    public function setCallerClass($callerClass)
    {
        $this->classInfo = new ClassInfo();
        $this->classInfo->setClass($callerClass);

        return $this;
    }

    /**
     * @return \Spryker\Shared\Kernel\ClassResolver\ClassInfo
     */
    public function getClassInfo()
    {
        return $this->classInfo;
    }

    /**
     * @return bool
     */
    public function canResolve()
    {
        if (isset($this->classInfo) && $this->classInfo->getCallerClassName() !== null) {
            $cacheKey = $this->buildCacheKey();

            if (isset(static::$cache[$cacheKey])) {
                $this->resolvedClassName = static::$cache[$cacheKey];

                return true;
            }
        }

        $classNames = $this->buildClassNames();

        foreach ($classNames as $className) {
            if ($this->classExists($className)) {
                $this->resolvedClassName = $className;

                if (isset($cacheKey)) {
                    static::$cache[$cacheKey] = $className;
                }

                return true;
            }
        }

        return false;
    }

    /**
     * This is needed to be able to use `canResolve` in a loop for the DependencyInjectorResolver.
     * The cache would always return the first found Injector without the reset here.
     *
     * @deprecated This method can be removed together with the DependencyInjectors
     *
     * @return void
     */
    protected function unsetCurrentCacheEntry()
    {
        unset(static::$cache[$this->buildCacheKey()]);
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
     * @return \Spryker\Yves\Kernel\Controller\AbstractController
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
        foreach ($this->getCoreNamespaces() as $namespace) {
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

    /**
     * @return string
     */
    protected function buildCacheKey()
    {
        return get_class($this) . '-' . $this->classInfo->getCallerClassName();
    }
}
