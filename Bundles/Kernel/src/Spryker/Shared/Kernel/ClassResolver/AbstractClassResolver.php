<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Kernel\ClassResolver;

use Spryker\Shared\Kernel\ClassResolver\ClassNameFinder\ClassNameFinderInterface;
use Spryker\Shared\Kernel\ClassResolver\ResolvableCache\CacheReader\CacheReaderInterface;
use Spryker\Shared\Kernel\ClassResolver\ResolvableCache\CacheReader\CacheReaderPhp;
use Spryker\Shared\Kernel\KernelConfig;
use Spryker\Shared\Kernel\KernelSharedFactory;

abstract class AbstractClassResolver
{
    public const KEY_NAMESPACE = '%namespace%';
    public const KEY_BUNDLE = '%bundle%';
    public const KEY_CODE_BUCKET = '%codeBucket%';

    protected const CLASS_NAME_PATTERN = null;
    protected const RESOLVABLE_TYPE = null;

    /**
     * @var array|null
     */
    protected static $resolvableClassNamesCache;

    /**
     * @var bool|null
     */
    protected static $isCacheEnabled;

    /**
     * @var bool|null
     */
    protected static $isInstanceCacheEnabled;

    /**
     * @var string|null
     */
    protected static $storeName;

    /**
     * @var array|null
     */
    protected static $projectNamespaces;

    /**
     * @var array|null
     */
    protected static $coreNamespaces;

    /**
     * @var \Spryker\Shared\Kernel\KernelConfig
     */
    protected static $sharedConfig;

    /**
     * @var \Spryker\Shared\Kernel\KernelSharedFactory
     */
    protected static $sharedFactory;

    /**
     * @var string[]|null
     */
    protected static $resolvableTypeClassNamePatternMap;

    /**
     * @var \Spryker\Shared\Kernel\ClassResolver\ClassNameFinder\ClassNameFinderInterface
     */
    protected static $classNameFinder;

    /**
     * @var string
     */
    private $resolvedClassName;

    /**
     * @var \Spryker\Shared\Kernel\ClassResolver\ClassInfo
     */
    protected $classInfo;

    /**
     * @var object[]
     */
    protected static $cachedInstances = [];

    /**
     * @var \Spryker\Shared\Kernel\ClassResolver\ResolverCacheFactoryInterface
     */
    protected static $resolverCacheManager;

    /**
     * @var bool|null
     */
    protected static $useResolverCache;

    /**
     * @param object|string $callerClass
     *
     * @return object|null
     */
    abstract public function resolve($callerClass);

    /**
     * @param string $namespace
     * @param string|null $codeBucket
     *
     * @return string
     */
    abstract protected function buildClassName($namespace, $codeBucket = null);

    /**
     * @param object|string $callerClass
     *
     * @return object|null
     */
    public function doResolve($callerClass)
    {
        $this->setCallerClass($callerClass);

        $cacheKey = $this->findCacheKey();
        $isInstanceCacheEnabled = $this->isInstanceCacheEnabled();

        if ($cacheKey !== null && $isInstanceCacheEnabled && isset(static::$cachedInstances[$cacheKey])) {
            return static::$cachedInstances[$cacheKey];
        }

        $resolvedClassName = $this->resolveClassName($cacheKey);

        if ($resolvedClassName !== null) {
            $resolvedInstance = $this->createInstance($resolvedClassName);
            if ($cacheKey !== null && $isInstanceCacheEnabled) {
                static::$cachedInstances[$cacheKey] = $resolvedInstance;
            }

            return $resolvedInstance;
        }

        return null;
    }

    /**
     * @param object|string $callerClass
     *
     * @return $this
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
        if ($this->canUseCaching()) {
            $cacheKey = $this->getCacheKey();

            if ($this->hasCache($cacheKey)) {
                $this->resolvedClassName = $this->getCached($cacheKey);

                return true;
            }
        }

        $classNames = $this->buildClassNames();

        foreach ($classNames as $className) {
            if ($this->classExists($className)) {
                $this->resolvedClassName = $className;

                if (isset($cacheKey)) {
                    $this->addCache($cacheKey, $className);
                }

                return true;
            }
        }

        return false;
    }

    /**
     * @deprecated Will be removed without replacement. This is no longer required as all class name patterns are now
     * configurable in {@link \Spryker\Shared\Kernel\KernelConfig::getResolvableTypeClassNamePatternMap}
     *
     * @return string
     */
    protected function getClassPattern()
    {
        return '';
    }

    /**
     * @return string|null
     */
    protected function findCacheKey(): ?string
    {
        if (!$this->isCacheEnabled()) {
            return null;
        }

        return $this->getCacheKey();
    }

    /**
     * @param string|null $cacheKey
     *
     * @return string|null
     */
    protected function resolveClassName(?string $cacheKey = null): ?string
    {
        if ($cacheKey !== null && $this->hasCache($cacheKey)) {
            return $this->getCached($cacheKey);
        }

        return $this->findClassName();
    }

    /**
     * @return string|null
     */
    protected function findClassName(): ?string
    {
        $classNamePattern = $this->getResolvableTypeClassNamePatternMap()[static::RESOLVABLE_TYPE] ?? null;
        if ($classNamePattern === null) {
            return null;
        }

        return $this->getClassNameFinder()->findClassName($this->getClassInfo()->getModule(), $classNamePattern);
    }

    /**
     * @return string[]
     */
    protected function getResolvableTypeClassNamePatternMap(): array
    {
        if (static::$resolvableTypeClassNamePatternMap === null) {
            static::$resolvableTypeClassNamePatternMap = $this->getSharedConfig()->getResolvableTypeClassNamePatternMap();
        }

        return static::$resolvableTypeClassNamePatternMap;
    }

    /**
     * @return \Spryker\Shared\Kernel\ClassResolver\ClassNameFinder\ClassNameFinderInterface
     */
    protected function getClassNameFinder(): ClassNameFinderInterface
    {
        if (static::$classNameFinder === null) {
            static::$classNameFinder = $this->getSharedFactory()->createClassNameFinder();
        }

        return static::$classNameFinder;
    }

    /**
     * @param string|null $resolvedClassName
     *
     * @return object
     */
    protected function createInstance(?string $resolvedClassName = null)
    {
        if ($resolvedClassName !== null) {
            return new $resolvedClassName();
        }

        return new $this->resolvedClassName();
    }

    /**
     * @param string $cacheKey
     *
     * @return bool
     */
    protected function hasCache(string $cacheKey): bool
    {
        $cache = $this->getCache();

        return isset($cache[$cacheKey]);
    }

    /**
     * @param string $cacheKey
     *
     * @return string
     */
    protected function getCached(string $cacheKey): string
    {
        $cache = $this->getCache();

        return str_replace('\\\\', '\\', $cache[$cacheKey]);
    }

    /**
     * @param string $cacheKey
     * @param string $className
     *
     * @return void
     */
    protected function addCache(string $cacheKey, string $className): void
    {
        $cache = $this->getCache();

        $cache[$cacheKey] = $className;
    }

    /**
     * @return array
     */
    protected function getCache(): array
    {
        if (static::$resolvableClassNamesCache === null) {
            static::$resolvableClassNamesCache = [];

            if ($this->canUseCaching()) {
                static::$resolvableClassNamesCache = $this->createCacheReader()->read();
            }
        }

        return static::$resolvableClassNamesCache;
    }

    /**
     * @return \Spryker\Shared\Kernel\ClassResolver\ResolvableCache\CacheReader\CacheReaderInterface
     */
    protected function createCacheReader(): CacheReaderInterface
    {
        return new CacheReaderPhp($this->getSharedConfig());
    }

    /**
     * @return bool
     */
    protected function canUseCaching(): bool
    {
        if ($this->isCacheEnabled() === false || $this->classInfo === null || static::RESOLVABLE_TYPE === null) {
            return false;
        }

        return true;
    }

    /**
     * @return bool
     */
    protected function isCacheEnabled(): bool
    {
        if (static::$isCacheEnabled === null) {
            static::$isCacheEnabled = $this->getSharedConfig()->isResolvableClassNameCacheEnabled();
        }

        return static::$isCacheEnabled;
    }

    /**
     * @return \Spryker\Shared\Kernel\KernelConfig
     */
    protected function getSharedConfig(): KernelConfig
    {
        if (static::$sharedConfig === null) {
            static::$sharedConfig = new KernelConfig();
        }

        return static::$sharedConfig;
    }

    /**
     * @return \Spryker\Shared\Kernel\KernelSharedFactory
     */
    protected function getSharedFactory(): KernelSharedFactory
    {
        if (static::$sharedFactory === null) {
            static::$sharedFactory = new KernelSharedFactory();
            static::$sharedFactory->setSharedConfig($this->getSharedConfig());
        }

        return static::$sharedFactory;
    }

    /**
     * @return bool
     */
    protected function isInstanceCacheEnabled(): bool
    {
        if (static::$isInstanceCacheEnabled === null) {
            static::$isInstanceCacheEnabled = $this->getSharedConfig()->isResolvedInstanceCacheEnabled();
        }

        return static::$isInstanceCacheEnabled;
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
        if (!$this->canUseCaching()) {
            return;
        }

        $cache = $this->getCache();

        unset($cache[$this->getCacheKey()]);
    }

    /**
     * @param string $className
     *
     * @return bool
     */
    protected function classExists($className)
    {
        if (!$this->useResolverCache()) {
            return class_exists($className);
        }

        $cacheProvider = $this->getResolverCacheManager()->createClassResolverCacheProvider();

        return $cacheProvider->getCache()->classExists($className);
    }

    /**
     * @return bool
     */
    protected function useResolverCache(): bool
    {
        if (static::$useResolverCache === null) {
            static::$useResolverCache = $this->getResolverCacheManager()->useCache();
        }

        return static::$useResolverCache;
    }

    /**
     * @return \Spryker\Shared\Kernel\ClassResolver\ResolverCacheFactoryInterface
     */
    protected function getResolverCacheManager(): ResolverCacheFactoryInterface
    {
        if (static::$resolverCacheManager === null) {
            static::$resolverCacheManager = new ResolverCacheManager();
        }

        return static::$resolverCacheManager;
    }

    /**
     * @deprecated Use {@link \Spryker\Shared\Kernel\ClassResolver\AbstractClassResolver::getResolverCacheManager} instead.
     *
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
        if (!$this->canUseCaching()) {
            return new $this->resolvedClassName();
        }

        $cacheKey = $this->getCacheKey();

        if (!isset(static::$cachedInstances[$cacheKey])) {
            static::$cachedInstances[$cacheKey] = new $this->resolvedClassName();
        }

        return static::$cachedInstances[$cacheKey];
    }

    /**
     * @return string[]
     */
    private function buildClassNames()
    {
        $classNames = [];

        $classNames = $this->addProjectClassNames($classNames);
        $classNames = $this->addCoreClassNames($classNames);

        return $classNames;
    }

    /**
     * @param string[] $classNames
     *
     * @return string[]
     */
    private function addProjectClassNames(array $classNames)
    {
        $codeBucket = APPLICATION_CODE_BUCKET;
        foreach ($this->getProjectNamespaces() as $namespace) {
            if ($codeBucket !== '') {
                $classNames[] = $this->buildClassName($namespace, $codeBucket);
            }

            $classNames[] = $this->buildClassName($namespace);
        }

        return $classNames;
    }

    /**
     * @return string
     */
    protected function getStoreName(): string
    {
        if (static::$storeName === null) {
            static::$storeName = $this->getSharedConfig()->getCurrentStoreName();
        }

        return static::$storeName;
    }

    /**
     * @param string[] $classNames
     *
     * @return string[]
     */
    private function addCoreClassNames(array $classNames)
    {
        foreach ($this->getCoreNamespaces() as $namespace) {
            $classNames[] = $this->buildClassName($namespace);
        }

        return $classNames;
    }

    /**
     * @return string[]
     */
    protected function getProjectNamespaces()
    {
        if (static::$projectNamespaces === null) {
            static::$projectNamespaces = $this->getSharedConfig()->getProjectOrganizations();
        }

        return static::$projectNamespaces;
    }

    /**
     * @return string[]
     */
    protected function getCoreNamespaces()
    {
        if (static::$coreNamespaces === null) {
            static::$coreNamespaces = $this->getSharedConfig()->getCoreOrganizations();
        }

        return static::$coreNamespaces;
    }

    /**
     * @return string
     */
    protected function getCacheKey(): string
    {
        return $this->classInfo->getCacheKey(static::RESOLVABLE_TYPE);
    }
}
